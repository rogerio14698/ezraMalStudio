<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * <picture> responsive a partir de una imagen y sus variantes optimizadas.
 *
 * Uso:
 *   <x-picture :src="$galeria->image" :variants="$galeria->image_variants"
 *              :alt="$galeria->alt_text" sizes="(min-width: 1024px) 33vw, 100vw" />
 *
 *   Imagen principal (hero): loading="eager" fetchpriority="high".
 *   Imagen decorativa: alt="".
 *   Sin variantes (o una URL externa) se pinta un <img> normal.
 */
class Picture extends Component
{
    private const MIME_TYPES = [
        'avif' => 'image/avif',
        'webp' => 'image/webp',
    ];

    /** @var list<array{type: string, srcset: string}> */
    public array $sources = [];

    public ?string $fallbackSrc;

    public ?int $imgWidth;

    public ?int $imgHeight;

    public function __construct(
        public ?string $src = null,
        public ?array $variants = null,
        public string $alt = '',
        public string $sizes = '100vw',
        public string $loading = 'lazy',
        public ?string $fetchpriority = null,
        public ?string $imgClass = null,
        ?int $width = null,
        ?int $height = null,
    ) {
        $this->fallbackSrc = $this->url($src);
        $this->imgWidth = $width ?? ($variants['width'] ?? null);
        $this->imgHeight = $height ?? ($variants['height'] ?? null);
        $this->sources = $this->buildSources($variants['sources'] ?? []);
    }

    public function shouldRender(): bool
    {
        return filled($this->fallbackSrc);
    }

    public function render(): View
    {
        return view('components.picture');
    }

    /**
     * Un <source> por formato (AVIF antes que WebP), con anchos ascendentes.
     *
     * @return list<array{type: string, srcset: string}>
     */
    private function buildSources(array $formats): array
    {
        $sources = [];

        foreach (self::MIME_TYPES as $format => $mime) {
            $variants = collect($formats[$format] ?? [])->sortBy('width');

            if ($variants->isEmpty()) {
                continue;
            }

            $sources[] = [
                'type' => $mime,
                'srcset' => $variants
                    ->map(fn (array $variant) => $this->url($variant['path'])." {$variant['width']}w")
                    ->implode(', '),
            ];
        }

        return $sources;
    }

    /**
     * Rutas del disco de medios -> URL pública. Las URLs absolutas se respetan.
     */
    private function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', '/'])) {
            return $path;
        }

        return Storage::disk(config('media.disk'))->url($path);
    }
}
