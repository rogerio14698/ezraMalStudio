<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Ciclo de vida de un mensaje del formulario de contacto.
 */
enum EstadoContacto: string implements HasColor, HasLabel
{
    case NoLeido = 'no_leido';
    case Leido = 'leido';
    case Respondido = 'respondido';

    public function getLabel(): string
    {
        return match ($this) {
            self::NoLeido => 'No leído',
            self::Leido => 'Leído',
            self::Respondido => 'Respondido',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NoLeido => 'danger',
            self::Leido => 'warning',
            self::Respondido => 'success',
        };
    }
}
