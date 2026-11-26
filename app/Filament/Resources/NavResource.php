<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavResource\Pages;
use App\Models\CMSPaginas\Nav;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Enlaces de los menús (principal y pie). Se editan en ventana modal
 * y se ordenan arrastrando las filas.
 */
class NavResource extends Resource
{
    protected static ?string $model = Nav::class;

    protected static ?string $slug = 'menus';

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Web';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Menús';

    protected static ?string $modelLabel = 'enlace del menú';

    protected static ?string $pluralModelLabel = 'enlaces del menú';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('label')
                ->label('Texto del enlace')
                ->placeholder('Talleres')
                ->required()
                ->maxLength(60),
            TextInput::make('url')
                ->label('Destino')
                ->placeholder('#talleres, /contactos o https://instagram.com/exitstudio')
                ->helperText('#seccion para saltar dentro de la portada, /ruta para otra página o una URL completa.')
                ->required()
                ->maxLength(255),
            Select::make('location')
                ->label('Menú')
                ->options(Nav::LOCATIONS)
                ->default('header')
                ->placeholder('Elige en qué menú aparece')
                ->native(false)
                ->required(),
            Toggle::make('open_in_new_tab')
                ->label('Abrir en pestaña nueva')
                ->helperText('Recomendado solo para enlaces externos.'),
            Toggle::make('is_active')
                ->label('Visible')
                ->default(true),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->label('Texto')->searchable(),
                Tables\Columns\TextColumn::make('url')->label('Destino')->color('gray'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Menú')
                    ->formatStateUsing(fn (string $state) => Nav::LOCATIONS[$state] ?? $state)
                    ->badge(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Visible'),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->paginated(false)
            ->filters([
                Tables\Filters\SelectFilter::make('location')->label('Menú')->options(Nav::LOCATIONS),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNavs::route('/'),
        ];
    }
}
