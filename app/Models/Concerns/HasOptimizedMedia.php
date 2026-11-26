<?php

namespace App\Models\Concerns;

use App\Services\Media\MediaOptimizationService;

/**
 * Optimiza automáticamente los medios de un modelo al guardarlo,
 * venga el cambio de Filament, de un seeder o de tinker.
 *
 * El modelo declara qué columnas procesar:
 *
 *   // columna con la ruta de la imagen => columna JSON con sus variantes
 *   protected array $optimizedImages = ['image' => 'image_variants'];
 *
 *   // columna con la ruta del archivo => columna con su peso en bytes
 *   protected array $sizedFiles = ['pdf' => 'pdf_size'];
 *
 *   // otros archivos que solo hay que borrar al eliminar el registro
 *   protected array $plainFiles = ['video'];
 */
trait HasOptimizedMedia
{
    public static function bootHasOptimizedMedia(): void
    {
        static::saving(fn (self $model) => $model->syncOptimizedMedia());
        static::deleted(fn (self $model) => $model->purgeMediaFiles());
    }

    /**
     * Regenera variantes y pesos solo de las columnas que han cambiado,
     * y borra del disco los archivos que se han sustituido.
     */
    protected function syncOptimizedMedia(): void
    {
        $service = app(MediaOptimizationService::class);

        foreach ($this->optimizedImages ?? [] as $field => $variantsField) {
            if (! $this->isDirty($field)) {
                continue;
            }

            $service->deleteVariants($this->getOriginal($variantsField));
            $this->deleteReplacedFile($service, $field);

            $this->{$variantsField} = $service->optimizeImage($this->{$field});
        }

        foreach ($this->sizedFiles ?? [] as $field => $sizeField) {
            if (! $this->isDirty($field)) {
                continue;
            }

            $this->deleteReplacedFile($service, $field);
            $this->{$sizeField} = $service->fileSize($this->{$field});
        }

        foreach ($this->plainFiles ?? [] as $field) {
            if ($this->isDirty($field)) {
                $this->deleteReplacedFile($service, $field);
            }
        }
    }

    /**
     * Al eliminar el registro se borran originales y variantes.
     */
    protected function purgeMediaFiles(): void
    {
        $service = app(MediaOptimizationService::class);

        foreach ($this->optimizedImages ?? [] as $field => $variantsField) {
            $service->deleteVariants($this->{$variantsField});
            $service->deleteFiles($this->{$field});
        }

        $service->deleteFiles(array_map(
            fn (string $field) => $this->{$field},
            [...array_keys($this->sizedFiles ?? []), ...($this->plainFiles ?? [])],
        ));
    }

    private function deleteReplacedFile(MediaOptimizationService $service, string $field): void
    {
        $previous = $this->getOriginal($field);

        if ($this->exists && filled($previous) && $previous !== $this->{$field}) {
            $service->deleteFiles($previous);
        }
    }
}
