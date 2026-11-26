<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TallerResource\Pages;
use App\Filament\Support\ServicioForm;
use App\Models\Servicios\Taller;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Talleres: cursos de varias sesiones (sección "Talleres" de la web).
 * Formulario y tabla en ServicioForm, compartidos con Actividades.
 */
class TallerResource extends Resource
{
    protected static ?string $model = Taller::class;

    protected static ?string $slug = 'talleres';

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'taller';

    protected static ?string $pluralModelLabel = 'talleres';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(ServicioForm::schema('talleres', 'Taller de óleo para adultos'));
    }

    public static function table(Table $table): Table
    {
        return ServicioForm::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTallers::route('/'),
            'create' => Pages\CreateTaller::route('/create'),
            'edit' => Pages\EditTaller::route('/{record}/edit'),
        ];
    }
}
