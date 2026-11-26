<?php

namespace App\Services\Media;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Throwable;

/**
 * Genera variantes optimizadas (AVIF/WebP en varios anchos) de las imágenes
 * subidas y calcula el peso de archivos descargables (PDF, vídeo).
 *
 * Estructura del JSON que devuelve optimizeImage() y que se guarda en las
 * columnas *_variants de los modelos:
 *
 * {
 *   "width": 2400, "height": 1600,               // tamaño del original
 *   "sources": {
 *     "avif": [ {"size":"sm","width":480,"height":320,"path":"galeria/variants/foto-sm.avif"}, ... ],
 *     "webp": [ ... ]
 *   }
 * }
 *
 * Formatos y anchos se ordenan del más ligero al más pesado, que es el orden
 * que necesita el componente <x-picture>.
 */
class MediaOptimizationService
{
    private ImageManager $manager;

    private bool $avifSupported;

    public function __construct()
    {
        [$this->manager, $this->avifSupported] = $this->resolveDriver();
    }

    /**
     * Crea las variantes de una imagen ya guardada en el disco de medios.
     * Devuelve null si el archivo no existe, no es optimizable o falla el proceso;
     * en ese caso la web usará el original, así que la subida nunca se bloquea.
     */
    public function optimizeImage(?string $path): ?array
    {
        if (blank($path) || ! $this->isOptimizable($path)) {
            return null;
        }

        $storage = $this->disk();

        if (! $storage->exists($path)) {
            Log::warning('MediaOptimization: el archivo no existe.', ['path' => $path]);

            return null;
        }

        $generated = [];

        try {
            $source = $this->manager->read($storage->get($path));

            $result = [
                'width' => $source->width(),
                'height' => $source->height(),
                'sources' => [],
            ];

            foreach ($this->formats() as $format) {
                try {
                    $variants = $this->encodeFormat($source, $path, $format, $storage);
                } catch (Throwable $e) {
                    // Un formato que falla (p. ej. AVIF en un GD sin libavif) no tumba al resto.
                    Log::warning("MediaOptimization: no se pudo generar {$format}.", [
                        'path' => $path,
                        'error' => $e->getMessage(),
                    ]);

                    continue;
                }

                $generated = [...$generated, ...array_column($variants, 'path')];
                $result['sources'][$format] = $variants;
            }

            return $result['sources'] === [] ? null : $result;
        } catch (Throwable $e) {
            Log::error('MediaOptimization: error al optimizar la imagen.', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            // Limpia lo que se hubiera escrito a medias.
            $storage->delete($generated);

            return null;
        }
    }

    /**
     * Borra todas las variantes descritas en un JSON de optimizeImage().
     */
    public function deleteVariants(?array $variants): void
    {
        $paths = collect($variants['sources'] ?? [])
            ->flatten(1)
            ->pluck('path')
            ->filter()
            ->all();

        $this->deleteFiles($paths);
    }

    /**
     * Borra uno o varios archivos del disco de medios sin lanzar excepciones.
     */
    public function deleteFiles(array|string|null $paths): void
    {
        $paths = array_filter((array) $paths);

        if ($paths === []) {
            return;
        }

        try {
            $this->disk()->delete($paths);
        } catch (Throwable $e) {
            Log::warning('MediaOptimization: no se pudieron borrar archivos.', [
                'paths' => $paths,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Peso en bytes de un archivo del disco de medios (null si no existe).
     */
    public function fileSize(?string $path): ?int
    {
        if (blank($path)) {
            return null;
        }

        try {
            return $this->disk()->exists($path) ? $this->disk()->size($path) : null;
        } catch (Throwable $e) {
            Log::warning('MediaOptimization: no se pudo leer el peso del archivo.', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Peso legible para la web, p. ej. "2.4 MB".
     */
    public static function humanFileSize(?int $bytes): ?string
    {
        return $bytes === null ? null : Number::fileSize($bytes, precision: 1);
    }

    public function supportsAvif(): bool
    {
        return $this->avifSupported;
    }

    /**
     * Genera todos los anchos de un formato. Si el original es más pequeño que
     * un ancho, no se amplía: se crea una única variante con el ancho original.
     *
     * @return list<array{size: string, width: int, height: int, path: string}>
     */
    private function encodeFormat(ImageInterface $source, string $path, string $format, Filesystem $storage): array
    {
        $directory = trim(dirname($path), './').'/variants';
        $name = pathinfo($path, PATHINFO_FILENAME);
        $quality = (int) config("media.quality.{$format}", 80);
        $variants = [];

        foreach (config('media.breakpoints') as $size => $width) {
            $image = (clone $source)->scaleDown(width: $width);

            $encoded = $format === 'avif'
                ? $image->toAvif(quality: $quality)
                : $image->toWebp(quality: $quality);

            $variantPath = ltrim("{$directory}/{$name}-{$size}.{$format}", '/');
            $storage->put($variantPath, (string) $encoded);

            $variants[] = [
                'size' => $size,
                'width' => $image->width(),
                'height' => $image->height(),
                'path' => $variantPath,
            ];

            if ($width >= $source->width()) {
                break;
            }
        }

        return $variants;
    }

    /**
     * Formatos a generar, del más ligero al más pesado.
     *
     * @return list<string>
     */
    private function formats(): array
    {
        return $this->avifSupported ? ['avif', 'webp'] : ['webp'];
    }

    private function isOptimizable(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, config('media.optimizable_extensions'), true);
    }

    /**
     * Imagick si está instalado (mejor calidad y AVIF más rápido); si no, GD.
     *
     * @return array{0: ImageManager, 1: bool}
     */
    private function resolveDriver(): array
    {
        $avifEnabled = (bool) config('media.avif');

        if (extension_loaded('imagick')) {
            $avif = $avifEnabled && in_array('AVIF', \Imagick::queryFormats('AVIF'), true);

            return [new ImageManager(new ImagickDriver), $avif];
        }

        $avif = $avifEnabled
            && function_exists('imageavif')
            && (gd_info()['AVIF Support'] ?? false);

        return [new ImageManager(new GdDriver), $avif];
    }

    private function disk(): Filesystem
    {
        return Storage::disk(config('media.disk'));
    }
}
