<?php

namespace App\Filament\Resources\NavResource\Pages;

use App\Filament\Resources\NavResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNavs extends ManageRecords
{
    protected static string $resource = NavResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
