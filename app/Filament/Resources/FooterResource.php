<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterResource\Pages;
use App\Models\CMSPaginas\Footer;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Pie de página: datos de contacto, redes y horario.
 * La web usa el registro más reciente (Footer::current()).
 *
 * Estructura JSON guardada:
 *  - redes:   [{"red": "instagram", "url": "https://instagram.com/exitstudio"}]
 *  - horario: [{"dias": "Lunes a viernes", "horas": "10:00–14:00"}]
 */
class FooterResource extends Resource
{
    public const REDES = [
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'tiktok' => 'TikTok',
        'youtube' => 'YouTube',
        'x' => 'X (Twitter)',
        'linkedin' => 'LinkedIn',
        'whatsapp' => 'WhatsApp',
    ];

    protected static ?string $model = Footer::class;

    protected static ?string $slug = 'pie-de-pagina';

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static ?string $navigationGroup = 'Web';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Pie de página';

    protected static ?string $modelLabel = 'pie de página';

    protected static ?string $pluralModelLabel = 'pies de página';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Datos del estudio')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Nombre')
                        ->placeholder('ExitStudio')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Email de contacto')
                        ->placeholder('hola@exitstudio.es')
                        ->email()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->label('Teléfono')
                        ->placeholder('+34 600 123 456')
                        ->tel()
                        ->maxLength(30),
                    TextInput::make('address')
                        ->label('Dirección')
                        ->placeholder('Calle del Ave María 12, 28012 Madrid')
                        ->maxLength(255),
                    TextInput::make('google_maps_url')
                        ->label('Enlace de Google Maps')
                        ->placeholder('https://maps.app.goo.gl/AbCdEf123')
                        ->helperText('En Google Maps: Compartir → Copiar enlace.')
                        ->url()
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Texto breve')
                        ->placeholder('Estudio de pintura y dibujo para adultos e infancias en el centro de Madrid.')
                        ->rows(2)
                        ->columnSpanFull(),
                    TextInput::make('copyright')
                        ->label('Texto legal')
                        ->placeholder('© 2026 ExitStudio. Todos los derechos reservados.')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            Section::make('Redes sociales')
                ->schema([
                    Repeater::make('redes')
                        ->label('')
                        ->schema([
                            Select::make('red')
                                ->label('Red')
                                ->options(self::REDES)
                                ->placeholder('Elige la red')
                                ->native(false)
                                ->required(),
                            TextInput::make('url')
                                ->label('Enlace al perfil')
                                ->placeholder('https://instagram.com/exitstudio')
                                ->url()
                                ->required(),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->addActionLabel('Añadir red')
                        ->defaultItems(0),
                ]),

            Section::make('Horario')
                ->schema([
                    Repeater::make('horario')
                        ->label('')
                        ->schema([
                            TextInput::make('dias')
                                ->label('Días')
                                ->placeholder('Lunes a viernes')
                                ->required(),
                            TextInput::make('horas')
                                ->label('Horas')
                                ->placeholder('10:00–14:00 y 17:00–21:00')
                                ->required(),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->addActionLabel('Añadir franja')
                        ->defaultItems(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->label('Email')->placeholder('—'),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono')->placeholder('—'),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->since(),
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
            'index' => Pages\ListFooters::route('/'),
            'create' => Pages\CreateFooter::route('/create'),
            'edit' => Pages\EditFooter::route('/{record}/edit'),
        ];
    }
}
