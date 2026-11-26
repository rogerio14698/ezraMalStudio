<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoPageResource\Pages;
use App\Models\SEO\SeoPage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * SEO de las páginas fijas (portada, talleres, contacto…).
 * Lo que se deje vacío hereda de "Ajustes SEO y Google Ads".
 */
class SeoPageResource extends Resource
{
    protected static ?string $model = SeoPage::class;

    protected static ?string $slug = 'seo-por-pagina';

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'SEO y marketing';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'SEO por página';

    protected static ?string $modelLabel = 'SEO de página';

    protected static ?string $pluralModelLabel = 'SEO por página';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('page_key')
                ->label('Página')
                ->options(SeoPage::PAGES)
                ->placeholder('Elige la página')
                ->native(false)
                ->unique(ignoreRecord: true)
                ->required(),
            TextInput::make('meta_title')
                ->label('Título SEO')
                ->placeholder('Talleres de pintura en Madrid | ExitStudio')
                ->helperText('Máx. 70 caracteres. Vacío = título global.')
                ->maxLength(70),
            Textarea::make('meta_description')
                ->label('Descripción SEO')
                ->placeholder('Talleres de óleo, acuarela y dibujo para adultos e infancias en Lavapiés. Grupos de 8 personas.')
                ->helperText('Entre 120 y 160 caracteres.')
                ->rows(2)
                ->maxLength(160),
            TextInput::make('meta_keywords')
                ->label('Palabras clave')
                ->placeholder('talleres de pintura, clases de dibujo, Madrid')
                ->maxLength(255),
            TextInput::make('canonical_url')
                ->label('URL canónica')
                ->placeholder('https://exitstudio.es/talleres')
                ->helperText('Solo si la misma página existe en varias URLs. Normalmente vacío.')
                ->url()
                ->maxLength(255),
            FileUpload::make('og_image')
                ->label('Imagen para redes (Open Graph)')
                ->placeholder('JPG de 1200 × 630 px que se ve al compartir en WhatsApp o Facebook')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios(['1.91:1'])
                ->maxSize(2048)
                ->disk(config('media.disk'))
                ->directory('seo')
                ->visibility('public'),
            Toggle::make('is_indexable')
                ->label('Permitir que Google la indexe')
                ->helperText('Desactívalo solo en páginas que no deban salir en buscadores.')
                ->default(true),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_key')
                    ->label('Página')
                    ->formatStateUsing(fn (string $state) => SeoPage::PAGES[$state] ?? $state),
                Tables\Columns\TextColumn::make('meta_title')->label('Título SEO')->placeholder('Hereda el global')->limit(50),
                Tables\Columns\IconColumn::make('is_indexable')->label('Indexable')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSeoPages::route('/'),
        ];
    }
}
