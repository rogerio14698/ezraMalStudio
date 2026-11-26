<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    /** Sin botón "Nuevo": los mensajes solo llegan desde la web. */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
