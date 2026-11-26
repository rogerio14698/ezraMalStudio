<?php

namespace App\Filament\Resources;

use App\Enums\EstadoPublicacion;
use App\Filament\Resources\ArticuloResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Articulos\Articulos;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Artículos del blog/diario del estudio.
 * Se muestran en la web si están publicados y su fecha ya ha llegado (scope visible()).
 */
class ArticuloResource extends Resource
{
    protected static ?string $model = Articulos::class;

    protected static ?string $slug = 'articulos';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'artículo';

    protected static ?string $pluralModelLabel = 'artículos';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Contenido')
                ->columns(2)
                ->schema([
                    Fields::title('Cómo preparar un lienzo para pintar al óleo'),
                    Fields::slug('como-preparar-un-lienzo-para-oleo'),
                    Textarea::make('excerpt')
                        ->label('Entradilla')
                        ->placeholder('Imprimación, lijado y tensado: los tres pasos que alargan la vida de tu cuadro.')
                        ->helperText('Resumen corto que aparece en el listado de artículos.')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->label('Texto del artículo')
                        ->placeholder('Escribe aquí el artículo. Usa subtítulos (H2/H3) para separar apartados…')
                        ->helperText('Las imágenes insertadas en el texto no se optimizan; para fotos grandes usa la imagen principal.')
                        ->fileAttachmentsDisk(config('media.disk'))
                        ->fileAttachmentsDirectory('articulos/contenido')
                        ->fileAttachmentsVisibility('public')
                        ->columnSpanFull(),
                    TextInput::make('author')
                        ->label('Autor/a')
                        ->placeholder('Ezra Mal')
                        ->maxLength(255),
                    Textarea::make('fuentes')
                        ->label('Fuentes')
                        ->placeholder('Mayer, R. "Materiales y técnicas del arte", 1993.')
                        ->helperText('Bibliografía o enlaces, uno por línea.')
                        ->rows(2),
                ]),

            Section::make('Multimedia')
                ->description('La imagen principal se optimiza al guardar. Vídeo y PDF se cargan bajo demanda.')
                ->columns(2)
                ->collapsible()
                ->schema([
                    Fields::image('image', 'articulos', 'Imagen principal'),
                    Fields::alt('image_alt', 'Lienzo en blanco sobre caballete con botes de imprimación')->requiredWith('image'),
                    Fields::video('video', 'articulos/videos'),
                    Fields::poster('video_poster', 'articulos/posters')->requiredWith('video'),
                    Fields::pdf('pdf', 'articulos/pdf')->columnSpanFull(),
                ]),

            Section::make('Publicación')
                ->columns(2)
                ->schema([
                    Fields::status(),
                    DateTimePicker::make('published_at')
                        ->label('Fecha de publicación')
                        ->placeholder('20/01/2027 09:00')
                        ->helperText('Si pones una fecha futura, el artículo se mostrará a partir de ese momento.')
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->default(now()),
                ]),

            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->disk(config('media.disk'))
                    ->square()
                    ->size(48),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Autor/a')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicación')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sin fecha')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Estado')->options(EstadoPublicacion::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticulos::route('/'),
            'create' => Pages\CreateArticulo::route('/create'),
            'edit' => Pages\EditArticulo::route('/{record}/edit'),
        ];
    }
}
