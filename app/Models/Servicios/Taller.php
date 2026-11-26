<?php

namespace App\Models\Servicios;

use App\Enums\EstadoPublicacion;
use App\Enums\Publico;
use App\Models\Concerns\HasOptimizedMedia;
use App\Models\Concerns\HasPublicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Taller regular (curso). Uso en la web: Taller::publicado()->ordenado()->get().
 */
class Taller extends Model
{
    /** @use HasFactory<\Database\Factories\Servicios\TallerFactory> */
    use HasFactory, HasOptimizedMedia, HasPublicationStatus;

    protected $table = 'tallers';

    protected $fillable = [
        'title',
        'slug',
        'publico',
        'summary',
        'description',
        'image',
        'image_alt',
        'video',
        'video_poster',
        'pdf',
        'price',
        'starts_at',
        'horario',
        'duration',
        'level',
        'category',
        'tags',
        'plazas',
        'status',
        'orden',
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
            'publico' => Publico::class,
            'status' => EstadoPublicacion::class,
            'image_variants' => 'array',
            'video_poster_variants' => 'array',
            'tags' => 'array',
            'price' => 'decimal:2',
            'starts_at' => 'datetime',
            'plazas' => 'integer',
            'pdf_size' => 'integer',
            'orden' => 'integer',
        ];
    }
}
