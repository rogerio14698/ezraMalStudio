<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Tipo de elemento dentro de una colección de galería.
 */
enum TipoMedia: string implements HasLabel
{
    case Imagen = 'imagen';
    case Video = 'video';
    case Pdf = 'pdf';

    public function getLabel(): string
    {
        return match ($this) {
            self::Imagen => 'Imagen',
            self::Video => 'Vídeo',
            self::Pdf => 'PDF',
        };
    }
}
