<?php

namespace App\Models\CMSPaginas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Pie de página: contacto, redes y horario (una sola fila: Footer::current()).
 */
class Footer extends Model
{
    /** @use HasFactory<\Database\Factories\CMSPaginas\FooterFactory> */
    use HasFactory;

    protected $table = 'footers';

    protected $fillable = [
        'title',
        'description',
        'phone',
        'email',
        'address',
        'google_maps_url',
        'redes',
        'horario',
        'copyright',
    ];

    protected function casts(): array
    {
        return [
            'redes' => 'array',
            'horario' => 'array',
        ];
    }

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }
}
