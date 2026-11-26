<?php

namespace App\Models\Galerias;

use App\Enums\TipoMedia;
use App\Models\Concerns\HasOptimizedMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Elemento de una colección de galería (imagen, vídeo o PDF).
 */
class Galeria extends Model
{
    /** @use HasFactory<\Database\Factories\Galerias\GaleriaFactory> */
    use HasFactory, HasOptimizedMedia;

    protected $table = 'galerias';

    protected $fillable = [
        'coleccion_id',
        'tipo',
        'title',
        'description',
        'image',
        'alt_text',
        'video',
        'video_poster',
        'pdf',
        'orden',
        'is_active',
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
            'tipo' => TipoMedia::class,
            'image_variants' => 'array',
            'video_poster_variants' => 'array',
            'pdf_size' => 'integer',
            'orden' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Los elementos nuevos se colocan al final de su colección.
        static::creating(function (self $galeria) {
            if (! $galeria->orden && $galeria->coleccion_id) {
                $galeria->orden = (int) static::where('coleccion_id', $galeria->coleccion_id)->max('orden') + 1;
            }
        });
    }

    public function coleccion(): BelongsTo
    {
        return $this->belongsTo(GaleriaColeccion::class, 'coleccion_id');
    }
}
