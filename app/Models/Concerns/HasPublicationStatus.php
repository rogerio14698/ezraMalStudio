<?php

namespace App\Models\Concerns;

use App\Enums\EstadoPublicacion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Comportamiento común de contenidos publicables con slug:
 * - Genera el slug a partir del título si se deja vacío.
 * - Scopes ->publicado() y ->ordenado().
 *
 * El modelo puede cambiar los nombres de columna con las constantes
 * SLUG_SOURCE (por defecto 'title') y STATUS_COLUMN (por defecto 'status').
 */
trait HasPublicationStatus
{
    public static function bootHasPublicationStatus(): void
    {
        static::saving(function (self $model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug($model->{static::slugSource()} ?? '');
            }
        });
    }

    public function scopePublicado(Builder $query): Builder
    {
        return $query->where(static::statusColumn(), EstadoPublicacion::Publicado);
    }

    public function scopeOrdenado(Builder $query): Builder
    {
        return $query->orderBy('orden')->orderByDesc('id');
    }

    protected static function slugSource(): string
    {
        return defined(static::class.'::SLUG_SOURCE') ? static::SLUG_SOURCE : 'title';
    }

    protected static function statusColumn(): string
    {
        return defined(static::class.'::STATUS_COLUMN') ? static::STATUS_COLUMN : 'status';
    }

    /**
     * "Óleo para principiantes" -> "oleo-para-principiantes" (o "-2", "-3"... si ya existe).
     */
    private function uniqueSlug(string $text): string
    {
        $base = Str::slug($text) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->whereKeyNot($this->getKey())->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
