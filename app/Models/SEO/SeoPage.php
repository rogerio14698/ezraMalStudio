<?php

namespace App\Models\SEO;

use Illuminate\Database\Eloquent\Model;

/**
 * SEO de una página estática. Uso: SeoPage::forKey('home')?->meta_title.
 */
class SeoPage extends Model
{
    /** Páginas que se pueden configurar desde el panel. */
    public const PAGES = [
        'home' => 'Portada',
        'talleres' => 'Talleres',
        'actividades' => 'Actividades',
        'galeria' => 'Galería',
        'articulos' => 'Artículos',
        'contacto' => 'Contacto',
    ];

    protected $table = 'seo_pages';

    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
        'is_indexable',
    ];

    protected function casts(): array
    {
        return [
            'is_indexable' => 'boolean',
        ];
    }

    public static function forKey(string $key): ?self
    {
        return static::query()->where('page_key', $key)->first();
    }
}
