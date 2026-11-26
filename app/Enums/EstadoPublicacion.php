<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Estado editorial compartido por talleres, actividades, artículos y galerías.
 * Solo lo 'publicado' se muestra en la web pública.
 */
enum EstadoPublicacion: string implements HasColor, HasLabel
{
    case Borrador = 'borrador';
    case Publicado = 'publicado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Borrador => 'Borrador',
            self::Publicado => 'Publicado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Borrador => 'gray',
            self::Publicado => 'success',
        };
    }
}
