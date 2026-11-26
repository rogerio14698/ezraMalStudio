<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActividadResource\Pages;
use App\Filament\Support\ServicioForm;
use App\Models\Servicios\Actividades;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Actividades puntuales (sesiones sueltas, visitas, eventos).
 * Formulario y tabla en ServicioForm, compartidos con Talleres.
 */
class ActividadResource extends Resource
{
    protected static ?string $model = Actividades::class;

    protected static ?string $slug = 'actividades';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'actividad';

    protected static ?string $pluralModelLabel = 'actividades';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(ServicioForm::schema('actividades', 'Sesión abierta de dibujo con modelo'));
    }

    public static function table(Table $table): Table
    {
        return ServicioForm::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActividads::route('/'),
            'create' => Pages\CreateActividad::route('/create'),
            'edit' => Pages\EditActividad::route('/{record}/edit'),
        ];
    }
}
