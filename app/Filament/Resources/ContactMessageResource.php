<?php

namespace App\Filament\Resources;

use App\Enums\EstadoContacto;
use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\Contacto\ContactMessage;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Bandeja de mensajes del formulario público de contacto.
 * Solo lectura: los mensajes llegan desde la web (Fase 3), no se crean aquí.
 * Al abrir un mensaje se marca como leído automáticamente.
 */
class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $slug = 'mensajes';

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Contacto';

    protected static ?string $navigationLabel = 'Mensajes';

    protected static ?string $modelLabel = 'mensaje';

    protected static ?string $pluralModelLabel = 'mensajes';

    protected static ?string $recordTitleAttribute = 'subject';

    public static function canCreate(): bool
    {
        return false;
    }

    /** Número de mensajes sin leer junto al menú. */
    public static function getNavigationBadge(): ?string
    {
        $pendientes = ContactMessage::query()->noLeidos()->count();

        return $pendientes > 0 ? (string) $pendientes : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function mailtoUrl(ContactMessage $message): string
    {
        $asunto = 'Re: '.($message->subject ?: 'Tu mensaje a '.config('app.name'));

        return 'mailto:'.$message->email.'?subject='.rawurlencode($asunto);
    }

    /** Marca como respondido con aviso; se usa en la tabla y en la vista. */
    public static function markAsReplied(ContactMessage $message): void
    {
        try {
            $message->markAsReplied();
            Notification::make()->success()->title('Marcado como respondido')->send();
        } catch (\Throwable $e) {
            Log::error('No se pudo actualizar el mensaje de contacto', ['id' => $message->id, 'error' => $e->getMessage()]);
            Notification::make()->danger()->title('No se pudo actualizar el mensaje')->send();
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Remitente')
                ->columns(3)
                ->schema([
                    TextEntry::make('name')->label('Nombre'),
                    TextEntry::make('email')->label('Email')->copyable()->copyMessage('Email copiado'),
                    TextEntry::make('phone')->label('Teléfono')->placeholder('No indicado')->copyable(),
                ]),
            Section::make('Mensaje')
                ->schema([
                    TextEntry::make('subject')->label('Asunto')->placeholder('Sin asunto'),
                    TextEntry::make('message')->label('Mensaje')->columnSpanFull(),
                ]),
            Section::make('Seguimiento')
                ->columns(3)
                ->collapsible()
                ->schema([
                    TextEntry::make('status')->label('Estado')->badge(),
                    TextEntry::make('created_at')->label('Recibido')->dateTime('d/m/Y H:i'),
                    TextEntry::make('replied_at')->label('Respondido')->dateTime('d/m/Y H:i')->placeholder('Pendiente'),
                    TextEntry::make('ip_address')->label('IP')->placeholder('—'),
                    TextEntry::make('user_agent')->label('Navegador')->placeholder('—')->columnSpan(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->description(fn (ContactMessage $record) => $record->email)
                    ->searchable(['name', 'email']),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Asunto')
                    ->placeholder('Sin asunto')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recibido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Estado')->options(EstadoContacto::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Leer'),
                Tables\Actions\Action::make('responder')
                    ->label('Marcar respondido')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (ContactMessage $record) => $record->status !== EstadoContacto::Respondido)
                    ->action(fn (ContactMessage $record) => self::markAsReplied($record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('marcarLeidos')
                        ->label('Marcar como leídos')
                        ->icon('heroicon-o-envelope-open')
                        ->action(fn (Collection $records) => $records->each->markAsRead())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No hay mensajes')
            ->emptyStateDescription('Aquí aparecerán los mensajes enviados desde el formulario de contacto.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
