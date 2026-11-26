<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriaColeccionResource\Pages;
use App\Filament\Resources\GaleriaColeccionResource\RelationManagers\ElementosRelationManager;
use App\Filament\Support\Fields;
use App\Models\Galerias\GaleriaColeccion;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Colecciones de la galería (p. ej. "Óleo 2026", "Trabajos de infancias").
 * Los elementos (fotos, vídeos, PDFs) se gestionan y reordenan en la
 * pestaña inferior de cada colección (ElementosRelationManager).
 */
class GaleriaColeccionResource extends Resource
{
    protected static ?string $model = GaleriaColeccion::class;

    protected static ?string $slug = 'colecciones';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Galería';

    protected static ?string $navigationLabel = 'Colecciones';

    protected static ?string $modelLabel = 'colección';

    protected static ?string $pluralModelLabel = 'colecciones';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Colección')
                ->description('Guarda la colección y después añade sus fotos, vídeos o PDFs en la tabla de abajo.')
                ->columns(2)
                ->schema([
                    Fields::title('Óleo 2026', 'titulo'),
                    Fields::slug('oleo-2026'),
                    Textarea::make('descripcion')
                        ->label('Descripción')
                        ->placeholder('Obras realizadas por el grupo de adultos durante el curso 2025-2026.')
                        ->helperText('Texto breve que aparece sobre la colección en la web.')
                        ->rows(3)
                        ->columnSpanFull(),
                    Fields::image('portada', 'galeria/portadas', 'Imagen de portada')
                        ->helperText('Opcional. Si la dejas vacía se usa el primer elemento de la colección.')
                        ->columnSpanFull(),
                    Fields::status('estado'),
                    Fields::orden(),
                ]),

            Fields::seo(keywords: false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('portada')
                    ->label('')
                    ->disk(config('media.disk'))
                    ->square()
                    ->size(48),
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('elementos_count')
                    ->label('Elementos')
                    ->counts('elementos')
                    ->badge(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->since()
                    ->toggleable(),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar y ver elementos'),
                Tables\Actions\DeleteAction::make()
                    ->modalDescription('Se borrarán también todos los elementos y archivos de la colección.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ElementosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGaleriaColeccions::route('/'),
            'create' => Pages\CreateGaleriaColeccion::route('/create'),
            'edit' => Pages\EditGaleriaColeccion::route('/{record}/edit'),
        ];
    }
}
