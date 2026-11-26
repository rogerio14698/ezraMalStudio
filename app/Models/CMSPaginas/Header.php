<?php

namespace App\Models\CMSPaginas;

use App\Models\Concerns\HasOptimizedMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Cabecera / hero de la portada (una sola fila: Header::current()).
 */
class Header extends Model
{
    /** @use HasFactory<\Database\Factories\CMSPaginas\HeaderFactory> */
    use HasFactory, HasOptimizedMedia;

    protected $table = 'headers';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'logo',
        'favicon',
        'background',
        'background_alt',
        'cta_label',
        'cta_url',
        'is_active',
    ];

    protected array $optimizedImages = ['background' => 'background_variants'];

    protected array $plainFiles = ['logo', 'favicon'];

    protected function casts(): array
    {
        return [
            'background_variants' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public static function current(): ?self
    {
        return static::query()->where('is_active', true)->latest('id')->first();
    }
}
