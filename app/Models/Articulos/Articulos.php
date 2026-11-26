<?php

namespace App\Models\Articulos;

use App\Enums\EstadoPublicacion;
use App\Models\Concerns\HasOptimizedMedia;
use App\Models\Concerns\HasPublicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Artículo del blog. Uso en la web: Articulos::visible()->latest('published_at')->get().
 */
class Articulos extends Model
{
    /** @use HasFactory<\Database\Factories\Articulos\ArticulosFactory> */
    use HasFactory, HasOptimizedMedia, HasPublicationStatus;

    protected $table = 'articulos';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'image_alt',
        'video',
        'video_poster',
        'pdf',
        'published_at',
        'status',
        'author',
        'fuentes',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected array $optimizedImages = [
        'image' => 'image_variants',
        'video_poster' => 'video_poster_variants',
    ];

    protected array $sizedFiles = ['pdf' => 'pdf_size'];

    protected array $plainFiles = ['video'];

    protected function casts(): array
    {
        return [
            'status' => EstadoPublicacion::class,
            'image_variants' => 'array',
            'video_poster_variants' => 'array',
            'published_at' => 'datetime',
            'pdf_size' => 'integer',
        ];
    }

    /**
     * Publicados y con fecha de publicación alcanzada (permite programar artículos).
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->publicado()
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }
}
