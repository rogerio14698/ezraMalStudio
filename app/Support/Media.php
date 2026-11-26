<?php

namespace App\Support;

use App\Services\Media\MediaOptimizationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Ayudas pequeñas para pintar medios en las vistas públicas.
 */
final class Media
{
    /** URL pública de una ruta del disco de medios; respeta URLs absolutas (p. ej. fotos de demo). */
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '//'])
            ? $path
            : Storage::disk(config('media.disk'))->url($path);
    }

    /**
     * Imagen ligera para el atributo poster de <video>: la variante WebP "md"
     * si existe; si no, el original.
     */
    public static function posterUrl(?string $path, ?array $variants): ?string
    {
        $webp = collect($variants['sources']['webp'] ?? []);
        $md = $webp->firstWhere('size', 'md') ?? $webp->last();

        return self::url($md['path'] ?? $path);
    }

    public static function size(?int $bytes): ?string
    {
        return MediaOptimizationService::humanFileSize($bytes);
    }
}
