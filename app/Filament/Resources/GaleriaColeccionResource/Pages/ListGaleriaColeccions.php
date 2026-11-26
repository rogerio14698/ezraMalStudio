<?php

namespace App\Filament\Resources\GaleriaColeccionResource\Pages;

use App\Filament\Resources\GaleriaColeccionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaleriaColeccions extends ListRecords
{
    protected static string $resource = GaleriaColeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
