<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Público al que va dirigido un taller o actividad
 * (alimenta las pestañas "Adultos / Infancias" de la web).
 */
enum Publico: string implements HasLabel
{
    case Adultos = 'adultos';
    case Infancias = 'infancias';
    case Todos = 'todos';

    public function getLabel(): string
    {
        return match ($this) {
            self::Adultos => 'Adultos',
            self::Infancias => 'Infancias y jóvenes',
            self::Todos => 'Todos los públicos',
        };
    }
}
