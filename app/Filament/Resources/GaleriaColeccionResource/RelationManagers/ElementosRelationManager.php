<?php

namespace App\Filament\Resources\GaleriaColeccionResource\RelationManagers;

use App\Enums\TipoMedia;
use App\Filament\Support\Fields;
use App\Models\Galerias\Galeria;
use App\Services\Media\MediaOptimizationService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Elementos de una colección: imagen, vídeo o PDF.
 * Se reordenan arrastrando filas (columna `orden`); los nuevos van al final
 * (lo resuelve el modelo Galeria al crearse).
 */
class ElementosRelationManager extends RelationManager
{
    protected static string $relationship = 'elementos';

    protected static ?string $title = 'Elementos de la colección';

    protected static ?string $modelLabel = 'elemento';

    protected static ?string $pluralModelLabel = 'elementos';

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('tipo')
                ->label('Tipo de elemento')
                ->options(TipoMedia::class)
                ->default(TipoMedia::Imagen->value)
                ->placeholder('Elige imagen, vídeo o PDF')
                ->helperText('Los campos de abajo cambian según el tipo.')
                ->native(false)
                ->required()
                ->live(),
            TextInput::make('title')
                ->label('Título')
                ->placeholder('Bodegón con granadas — Lucía, 2026')
                ->helperText('Opcional para imágenes; obligatorio para PDFs.')
                ->required(fn (Get $get) => self::is($get, TipoMedia::Pdf))
                ->maxLength(255),
            Textarea::make('description')
                ->label('Descripción')
                ->placeholder('Óleo sobre lienzo, 40 × 50 cm. Trabajo final del taller de adultos.')
                ->rows(2)
                ->columnSpanFull(),

            Fields::image('image', 'galeria')
                ->visible(fn (Get $get) => self::is($get, TipoMedia::Imagen))
                ->required(fn (Get $get) => self::is($get, TipoMedia::Imagen))
                ->columnSpanFull(),
            Fields::video('video', 'galeria/videos')
                ->visible(fn (Get $get) => self::is($get, TipoMedia::Video))
                ->required(fn (Get $get) => self::is($get, TipoMedia::Video)),
            Fields::poster('video_poster', 'galeria/posters')
                ->visible(fn (Get $get) => self::is($get, TipoMedia::Video))
                ->required(fn (Get $get) => self::is($get, TipoMedia::Video)),
            Fields::alt('alt_text', 'Bodegón al óleo con tres granadas sobre un paño azul')
                ->visible(fn (Get $get) => ! self::is($get, TipoMedia::Pdf))
                ->required(fn (Get $get) => self::is($get, TipoMedia::Imagen))
                ->columnSpanFull(),
            Fields::pdf('pdf', 'galeria/pdf')
                ->visible(fn (Get $get) => self::is($get, TipoMedia::Pdf))
                ->required(fn (Get $get) => self::is($get, TipoMedia::Pdf))
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Visible en la web')
                ->helperText('Desactívalo para ocultarlo sin borrarlo.')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->disk(config('media.disk'))
                    ->getStateUsing(fn (Galeria $record) => $record->image ?: $record->video_poster)
                    ->square()
                    ->size(56),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->placeholder('Sin título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge(),
                Tables\Columns\TextColumn::make('pdf_size')
                    ->label('Peso PDF')
                    ->formatStateUsing(fn (?int $state) => MediaOptimizationService::humanFileSize($state))
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Visible'),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->paginated(false)
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')->label('Tipo')->options(TipoMedia::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Añadir elemento'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Esta colección está vacía')
            ->emptyStateDescription('Pulsa "Añadir elemento" para subir la primera foto, vídeo o PDF.');
    }

    /** El estado puede llegar como string ('imagen') o como enum según el ciclo de Livewire. */
    private static function is(Get $get, TipoMedia $tipo): bool
    {
        $valor = $get('tipo');

        return ($valor instanceof TipoMedia ? $valor : TipoMedia::tryFrom((string) $valor)) === $tipo;
    }
}
