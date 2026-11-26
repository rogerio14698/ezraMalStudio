<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Enums\EstadoContacto;
use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    /** Abrir el mensaje lo marca como leído. */
    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->markAsRead();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('email')
                ->label('Responder por email')
                ->icon('heroicon-o-envelope')
                ->url(fn () => ContactMessageResource::mailtoUrl($this->record)),
            Actions\Action::make('responder')
                ->label('Marcar como respondido')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn () => $this->record->status !== EstadoContacto::Respondido)
                ->action(fn () => ContactMessageResource::markAsReplied($this->record)),
            Actions\DeleteAction::make(),
        ];
    }
}
