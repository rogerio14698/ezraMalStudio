<?php

namespace App\Models\Galerias;

use App\Enums\EstadoPublicacion;
use App\Models\Concerns\HasOptimizedMedia;
use App\Models\Concerns\HasPublicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Colección de galería. Uso en la web:
 *   GaleriaColeccion::publicado()->ordenado()->with('elementosVisibles')->get()
 */
class GaleriaColeccion extends Model
{
    use HasOptimizedMedia, HasPublicationStatus;

    public const SLUG_SOURCE = 'titulo';

    public const STATUS_COLUMN = 'estado';

    protected $table = 'galeria_colecciones';

    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
        'portada',
        'orden',
        'estado',
        'seo_title',
        'seo_description',
    ];

    protected array $optimizedImages = ['portada' => 'portada_variants'];

    protected function casts(): array
    {
        return [
            'estado' => EstadoPublicacion::class,
            'portada_variants' => 'array',
            'orden' => 'integer',
        ];
    }

    /**
     * Todos los elementos (para el panel), en su orden.
     */
    public function elementos(): HasMany
    {
        return $this->hasMany(Galeria::class, 'coleccion_id')->orderBy('orden');
    }

    /**
     * Solo los elementos activos (para la web pública).
     */
    public function elementosVisibles(): HasMany
    {
        return $this->elementos()->where('is_active', true);
    }
}
