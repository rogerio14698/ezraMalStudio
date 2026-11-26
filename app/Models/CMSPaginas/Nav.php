<?php

namespace App\Models\CMSPaginas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Enlace de navegación. Uso: Nav::menu('header')->get().
 */
class Nav extends Model
{
    /** @use HasFactory<\Database\Factories\CMSPaginas\NavFactory> */
    use HasFactory;

    public const LOCATIONS = [
        'header' => 'Menú principal',
        'footer' => 'Menú del pie',
    ];

    protected $table = 'navs';

    protected $fillable = [
        'label',
        'url',
        'location',
        'orden',
        'open_in_new_tab',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'open_in_new_tab' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Enlaces activos de una ubicación, en su orden.
     */
    public function scopeMenu(Builder $query, string $location): Builder
    {
        return $query->where('location', $location)
            ->where('is_active', true)
            ->orderBy('orden');
    }
}
