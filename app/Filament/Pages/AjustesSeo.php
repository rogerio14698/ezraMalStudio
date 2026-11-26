<?php

namespace App\Filament\Pages;

use App\Models\SEO\SeoSetting;
use App\Services\Media\MediaOptimizationService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;

/**
 * Ajustes globales de SEO, Google Ads, Analytics y UTM.
 *
 * Es un registro único (seo_settings): esta página lo crea la primera vez
 * y después lo edita. El modelo limpia su caché al guardar, y sus métodos
 * (googleAdsConversionId(), conversionSendTo()…) vuelven a validar el
 * formato antes de que nada se imprima en el layout público.
 */
class AjustesSeo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'SEO y marketing';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Ajustes SEO y Google Ads';

    protected static ?string $title = 'Ajustes SEO y Google Ads';

    protected static ?string $slug = 'ajustes-seo';

    protected static string $view = 'filament.pages.ajustes-seo';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SeoSetting::query()->first()?->attributesToArray() ?? [
            'site_name' => config('app.name'),
            'title_separator' => '·',
            'robots' => 'index, follow',
            'utm_tracking_enabled' => true,
            'utm_parameters' => SeoSetting::DEFAULT_UTM_PARAMETERS,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Metaetiquetas globales')
                    ->description('Se usan en cualquier página que no tenga su propio SEO.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nombre del sitio')
                            ->placeholder('ExitStudio')
                            ->required()
                            ->maxLength(255),
                        Select::make('title_separator')
                            ->label('Separador del título')
                            ->options([
                                '·' => '·  (Talleres · ExitStudio)',
                                '|' => '|  (Talleres | ExitStudio)',
                                '-' => '-  (Talleres - ExitStudio)',
                                '—' => '—  (Talleres — ExitStudio)',
                            ])
                            ->placeholder('Elige cómo se separa el título del nombre')
                            ->native(false)
                            ->required(),
                        TextInput::make('default_title')
                            ->label('Título por defecto')
                            ->placeholder('Estudio de pintura y dibujo en Madrid')
                            ->helperText('Se completa con el separador y el nombre del sitio. Máx. 70 caracteres.')
                            ->maxLength(70),
                        TextInput::make('default_keywords')
                            ->label('Palabras clave')
                            ->placeholder('estudio de arte, clases de pintura, Lavapiés')
                            ->maxLength(255),
                        Textarea::make('default_description')
                            ->label('Descripción por defecto')
                            ->placeholder('Talleres de pintura y dibujo para adultos e infancias en Lavapiés, Madrid. Grupos reducidos y material incluido.')
                            ->helperText('Entre 120 y 160 caracteres.')
                            ->rows(2)
                            ->maxLength(160)
                            ->columnSpanFull(),
                        Select::make('robots')
                            ->label('Indexación en buscadores')
                            ->options([
                                'index, follow' => 'Indexar y seguir enlaces (recomendado)',
                                'noindex, follow' => 'No indexar, pero seguir enlaces',
                                'noindex, nofollow' => 'No indexar nada (web en pruebas)',
                            ])
                            ->placeholder('Elige si Google puede mostrar la web')
                            ->native(false)
                            ->required(),
                        TextInput::make('google_site_verification')
                            ->label('Verificación de Google Search Console')
                            ->placeholder('aBcD1234efGH5678ijKL')
                            ->helperText('Pega solo el valor de content="…" de la etiqueta que te da Search Console.')
                            ->regex('/^[\w-]{10,100}$/')
                            ->maxLength(100),
                        FileUpload::make('default_og_image')
                            ->label('Imagen para redes por defecto (Open Graph)')
                            ->placeholder('JPG de 1200 × 630 px con el logo o una foto del estudio')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1.91:1'])
                            ->maxSize(2048)
                            ->disk(config('media.disk'))
                            ->directory('seo')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Google Ads')
                    ->description('Etiqueta de conversión de Google Ads. Se valida el formato antes de imprimirla en la web.')
                    ->columns(2)
                    ->schema([
                        Toggle::make('google_ads_enabled')
                            ->label('Activar Google Ads')
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('google_ads_conversion_id')
                            ->label('ID de conversión')
                            ->placeholder('AW-123456789')
                            ->helperText('En Google Ads: Objetivos → Conversiones → Configurar etiqueta.')
                            ->regex('/^AW-\d{6,15}$/')
                            ->validationMessages(['regex' => 'Debe tener el formato AW-123456789.'])
                            ->required(fn (Get $get) => (bool) $get('google_ads_enabled'))
                            ->maxLength(30),
                        Repeater::make('google_ads_conversion_labels')
                            ->label('Etiquetas de conversión por evento')
                            ->helperText('Cada evento de la web (p. ej. enviar el formulario de contacto) con su etiqueta de Google Ads.')
                            ->schema([
                                TextInput::make('evento')
                                    ->label('Evento')
                                    ->placeholder('contacto_enviado')
                                    ->alphaDash()
                                    ->required()
                                    ->maxLength(64),
                                TextInput::make('label')
                                    ->label('Etiqueta')
                                    ->placeholder('AbC-D_efG123hIj')
                                    ->helperText('Lo que va después de la barra en send_to: AW-123456789/ESTO.')
                                    ->regex('/^[\w-]{4,64}$/')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Añadir conversión')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Google Analytics 4')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('ID de medición')
                            ->placeholder('G-ABC123DEF4')
                            ->helperText('En Analytics: Administrar → Flujos de datos → ID de medición. Vacío = desactivado.')
                            ->regex('/^G-[A-Z0-9]{4,20}$/')
                            ->validationMessages(['regex' => 'Debe tener el formato G-ABC123DEF4.'])
                            ->maxLength(30),
                    ]),

                Section::make('Seguimiento de campañas (UTM)')
                    ->description('Guarda los parámetros de la URL de llegada para asociarlos a los mensajes de contacto.')
                    ->schema([
                        Toggle::make('utm_tracking_enabled')
                            ->label('Guardar parámetros de campaña')
                            ->live(),
                        TagsInput::make('utm_parameters')
                            ->label('Parámetros a guardar')
                            ->placeholder('Escribe un parámetro (ej. utm_campaign) y pulsa Enter')
                            ->helperText('Vacío = utm_source, utm_medium, utm_campaign, utm_term, utm_content y gclid.')
                            ->nestedRecursiveRules(['regex:/^[a-z0-9_]{2,40}$/'])
                            ->visible(fn (Get $get) => (bool) $get('utm_tracking_enabled')),
                    ]),

                Section::make('Scripts personalizados')
                    ->description('Para otros proveedores (Meta Pixel, Hotjar…). Google Ads y Analytics ya se configuran arriba.')
                    ->collapsed()
                    ->schema([
                        Textarea::make('head_scripts')
                            ->label('Código antes de </head>')
                            ->placeholder('<script async src="https://proveedor.com/pixel.js"></script>')
                            ->helperText('Se inserta sin modificar en todas las páginas públicas. Pega solo código de proveedores de confianza.')
                            ->rows(5)
                            ->maxLength(10000),
                        Textarea::make('body_scripts')
                            ->label('Código al final de <body>')
                            ->placeholder('<noscript><img height="1" width="1" src="https://proveedor.com/pixel?id=123"></noscript>')
                            ->helperText('Igual que el anterior: se inserta tal cual, revisa su origen.')
                            ->rows(5)
                            ->maxLength(10000),
                    ]),
            ]);
    }

    /** @return array<Action> */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar ajustes')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            $setting = SeoSetting::query()->firstOrNew();
            $imagenAnterior = $setting->default_og_image;

            $setting->fill($data)->save();

            if ($imagenAnterior && $imagenAnterior !== $setting->default_og_image) {
                app(MediaOptimizationService::class)->deleteFiles($imagenAnterior);
            }

            Notification::make()->success()->title('Ajustes guardados')->send();
        } catch (\Throwable $e) {
            Log::error('No se pudieron guardar los ajustes SEO', ['error' => $e->getMessage()]);

            Notification::make()
                ->danger()
                ->title('No se pudieron guardar los ajustes')
                ->body('Inténtalo de nuevo. Si se repite, revisa storage/logs/laravel.log.')
                ->send();
        }
    }
}
