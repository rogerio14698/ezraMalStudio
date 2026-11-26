<?php

namespace App\Filament\Support;

use App\Enums\EstadoPublicacion;
use App\Enums\Publico;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Formulario y tabla compartidos por Talleres y Actividades,
 * que tienen exactamente las mismas columnas.
 */
final class ServicioForm
{
    public static function schema(string $directory, string $ejemplo): array
    {
        return [
            Section::make('Información principal')
                ->columns(2)
                ->schema([
                    Fields::title($ejemplo),
                    Fields::slug(Str::slug($ejemplo)),
                    Select::make('publico')
                        ->label('Público')
                        ->options(Publico::class)
                        ->placeholder('Elige a quién va dirigido')
                        ->helperText('Decide en qué pestaña aparece: adultos o infancias.')
                        ->native(false)
                        ->required(),
                    TextInput::make('category')
                        ->label('Categoría')
                        ->placeholder('Pintura al óleo')
                        ->maxLength(255),
                    Textarea::make('summary')
                        ->label('Resumen')
                        ->placeholder('Iniciación al óleo en grupos reducidos: color, mezcla y composición.')
                        ->helperText('Una o dos frases. Se muestra en la tarjeta del listado.')
                        ->rows(2)
                        ->maxLength(255)
                        ->columnSpanFull(),
                    RichEditor::make('description')
                        ->label('Descripción completa')
                        ->placeholder('Qué se aprende, qué materiales incluye, qué hay que traer y cómo es cada sesión…')
                        ->disableToolbarButtons(['attachFiles'])
                        ->columnSpanFull(),
                ]),

            Section::make('Fecha, horario y plazas')
                ->columns(3)
                ->schema([
                    DateTimePicker::make('starts_at')
                        ->label('Fecha de inicio')
                        ->placeholder('15/01/2027 18:00')
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i'),
                    TextInput::make('horario')
                        ->label('Horario')
                        ->placeholder('Martes y jueves, 18:00–20:00')
                        ->maxLength(255),
                    TextInput::make('duration')
                        ->label('Duración')
                        ->placeholder('8 semanas (16 horas)')
                        ->maxLength(255),
                    TextInput::make('level')
                        ->label('Nivel')
                        ->placeholder('Iniciación')
                        ->maxLength(255),
                    TextInput::make('price')
                        ->label('Precio')
                        ->placeholder('120.00')
                        ->helperText('Precio total en euros. Vacío = "Consultar".')
                        ->numeric()
                        ->minValue(0)
                        ->step(0.01)
                        ->prefix('€'),
                    TextInput::make('plazas')
                        ->label('Plazas')
                        ->placeholder('8')
                        ->numeric()
                        ->minValue(1),
                    TagsInput::make('tags')
                        ->label('Etiquetas')
                        ->placeholder('Escribe una etiqueta (ej. óleo) y pulsa Enter')
                        ->columnSpanFull(),
                ]),

            Section::make('Multimedia')
                ->description('Las imágenes se optimizan al guardar. El vídeo y el PDF solo se cargan cuando el visitante los pide.')
                ->columns(2)
                ->collapsible()
                ->schema([
                    Fields::image('image', $directory),
                    Fields::alt('image_alt')->requiredWith('image'),
                    Fields::video('video', "{$directory}/videos"),
                    Fields::poster('video_poster', "{$directory}/posters")->requiredWith('video'),
                    Fields::pdf('pdf', "{$directory}/pdf")->columnSpanFull(),
                ]),

            Section::make('Publicación')
                ->columns(2)
                ->schema([
                    Fields::status(),
                    Fields::orden(),
                ]),

            Fields::seo(),
        ];
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
                    ->description(fn (Model $record) => Str::limit((string) $record->summary, 60))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publico')
                    ->label('Público')
                    ->badge(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sin fecha')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('EUR')
                    ->placeholder('Consultar')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Estado')->options(EstadoPublicacion::class),
                Tables\Filters\SelectFilter::make('publico')->label('Público')->options(Publico::class),
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
}
