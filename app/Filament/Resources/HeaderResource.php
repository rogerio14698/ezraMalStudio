<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeaderResource\Pages;
use App\Filament\Support\Fields;
use App\Models\CMSPaginas\Header;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Cabecera y portada (hero) de la web. La web usa la cabecera activa
 * más reciente (Header::current()), así se pueden preparar versiones.
 */
class HeaderResource extends Resource
{
    protected static ?string $model = Header::class;

    protected static ?string $slug = 'cabeceras';

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static ?string $navigationGroup = 'Web';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Cabecera y portada';

    protected static ?string $modelLabel = 'cabecera';

    protected static ?string $pluralModelLabel = 'cabeceras';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Textos de la portada')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Título principal')
                        ->placeholder('Un estudio para aprender a mirar')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('subtitle')
                        ->label('Subtítulo')
                        ->placeholder('Talleres de pintura y dibujo en Lavapiés, Madrid')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Descripción')
                        ->placeholder('Grupos reducidos, luz natural y todo el material incluido.')
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('cta_label')
                        ->label('Texto del botón')
                        ->placeholder('Ver talleres')
                        ->maxLength(255),
                    TextInput::make('cta_url')
                        ->label('Enlace del botón')
                        ->placeholder('#talleres o https://exitstudio.es/talleres')
                        ->helperText('Usa #seccion para saltar dentro de la página.')
                        ->requiredWith('cta_label')
                        ->maxLength(255),
                ]),

            Section::make('Imágenes')
                ->columns(2)
                ->schema([
                    FileUpload::make('logo')
                        ->label('Logotipo')
                        ->placeholder('Arrastra el logo en SVG o PNG transparente')
                        ->helperText('Mejor SVG: pesa poco y se ve nítido en cualquier pantalla.')
                        ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/webp'])
                        ->maxSize(1024)
                        ->disk(config('media.disk'))
                        ->directory('marca')
                        ->visibility('public'),
                    FileUpload::make('favicon')
                        ->label('Favicon')
                        ->placeholder('Icono cuadrado PNG o SVG de 512 × 512 px')
                        ->helperText('Aparece en la pestaña del navegador.')
                        ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                        ->maxSize(512)
                        ->disk(config('media.disk'))
                        ->directory('marca')
                        ->visibility('public'),
                    Fields::image('background', 'portada', 'Imagen de fondo de la portada')
                        ->helperText('Foto horizontal de al menos 1600 px. Es lo primero que carga la web: se servirá con prioridad alta.')
                        ->columnSpanFull(),
                    Fields::alt('background_alt', 'Mesa de trabajo del estudio con pinceles y lienzos a contraluz')
                        ->requiredWith('background')
                        ->columnSpanFull(),
                ]),

            Toggle::make('is_active')
                ->label('Cabecera activa')
                ->helperText('Si hay varias activas, la web usa la más reciente.')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('background')
                    ->label('')
                    ->disk(config('media.disk'))
                    ->size(48),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->since(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeaders::route('/'),
            'create' => Pages\CreateHeader::route('/create'),
            'edit' => Pages\EditHeader::route('/{record}/edit'),
        ];
    }
}
