<?php

namespace App\Filament\Support;

use App\Enums\EstadoPublicacion;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Support\Str;

/**
 * Campos de formulario reutilizables por todos los Resources del panel.
 *
 * Centraliza disco, límites de peso, editor de imágenes y textos de ayuda
 * para que todas las subidas se comporten igual. La optimización (WebP/AVIF
 * en 480/960/1600 px) no ocurre aquí: la hace el trait HasOptimizedMedia
 * del modelo al guardar, llamando a MediaOptimizationService.
 */
final class Fields
{
    /** Límites en KB. El de vídeo coincide con upload_max_filesize de PHP (40M). */
    public const IMAGE_MAX_KB = 8192;

    public const VIDEO_MAX_KB = 40960;

    public const PDF_MAX_KB = 10240;

    /**
     * Título que rellena el slug automáticamente solo al crear,
     * para no romper URLs ya publicadas al editar.
     */
    public static function title(string $placeholder, string $name = 'title', string $label = 'Título'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->placeholder($placeholder)
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                if ($operation === 'create') {
                    $set('slug', Str::slug((string) $state));
                }
            });
    }

    public static function slug(string $placeholder = 'taller-de-oleo-para-adultos'): TextInput
    {
        return TextInput::make('slug')
            ->label('URL amigable (slug)')
            ->placeholder($placeholder)
            ->helperText('Se genera desde el título. Solo minúsculas, números y guiones. Si lo dejas vacío se crea solo.')
            ->maxLength(255)
            ->alphaDash()
            ->unique(ignoreRecord: true);
    }

    /**
     * Imagen con editor (recorte/encuadre) y redimensionado en el navegador
     * a un máximo de 2400 px para no subir originales de 20 MB.
     */
    public static function image(string $name, string $directory, string $label = 'Imagen'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->placeholder('Arrastra una foto JPG, PNG o WebP (mín. 1600 px de ancho) o haz clic para elegirla')
            ->helperText('Pulsa el lápiz para recortar o encuadrar. Al guardar se crean versiones WebP/AVIF de 480, 960 y 1600 px.')
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1', '3:4'])
            ->imageResizeMode('contain')
            ->imageResizeTargetWidth('2400')
            ->imageResizeTargetHeight('2400')
            ->imageResizeUpscale(false)
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
            ->maxSize(self::IMAGE_MAX_KB)
            ->disk(config('media.disk'))
            ->directory($directory)
            ->visibility('public');
    }

    public static function poster(string $name, string $directory): FileUpload
    {
        return self::image($name, $directory, 'Imagen de portada del vídeo (poster)')
            ->placeholder('Fotograma representativo del vídeo en JPG o WebP (16:9)')
            ->helperText('Se muestra en lugar del vídeo hasta que el visitante pulsa reproducir. Se optimiza igual que las fotos.');
    }

    public static function video(string $name, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label('Vídeo')
            ->placeholder('Arrastra un vídeo MP4 o WebM (máx. 40 MB) o haz clic para elegirlo')
            ->helperText('En la web no se descarga hasta que se pulsa reproducir. Expórtalo a 1080p como máximo antes de subirlo.')
            ->acceptedFileTypes(['video/mp4', 'video/webm'])
            ->maxSize(self::VIDEO_MAX_KB)
            ->disk(config('media.disk'))
            ->directory($directory)
            ->visibility('public');
    }

    public static function pdf(string $name, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label('Documento PDF')
            ->placeholder('Arrastra un PDF (máx. 10 MB), por ejemplo el programa del taller')
            ->helperText('En la web se muestra con su título y peso; solo se abre o descarga si el visitante lo pide.')
            ->acceptedFileTypes(['application/pdf'])
            ->maxSize(self::PDF_MAX_KB)
            ->downloadable()
            ->openable()
            ->disk(config('media.disk'))
            ->directory($directory)
            ->visibility('public');
    }

    public static function alt(string $name, string $placeholder = 'Alumna pintando un retrato al óleo junto a la ventana del estudio'): TextInput
    {
        return TextInput::make($name)
            ->label('Texto alternativo (alt)')
            ->placeholder($placeholder)
            ->helperText('Describe lo que se ve, para lectores de pantalla y Google. Sin "imagen de…". Máx. 150 caracteres.')
            ->maxLength(150);
    }

    public static function status(string $name = 'status'): Select
    {
        return Select::make($name)
            ->label('Estado')
            ->options(EstadoPublicacion::class)
            ->default(EstadoPublicacion::Borrador->value)
            ->placeholder('Elige si se muestra en la web')
            ->helperText('"Borrador" lo guarda sin mostrarlo en la web.')
            ->native(false)
            ->required();
    }

    public static function orden(): TextInput
    {
        return TextInput::make('orden')
            ->label('Orden')
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->placeholder('0')
            ->helperText('Los números más bajos aparecen primero. También puedes arrastrar las filas en el listado.');
    }

    /** Bloque SEO plegado; las tablas sin columna seo_keywords pasan $keywords = false. */
    public static function seo(bool $keywords = true): Section
    {
        return Section::make('SEO')
            ->description('Opcional. Si lo dejas vacío se usan el título y el resumen.')
            ->collapsed()
            ->columns(2)
            ->schema(array_values(array_filter([
                TextInput::make('seo_title')
                    ->label('Título SEO')
                    ->placeholder('Taller de óleo para adultos en Madrid | ExitStudio')
                    ->helperText('Lo que aparece en la pestaña y en Google. Máx. 70 caracteres.')
                    ->maxLength(70),
                $keywords ? TextInput::make('seo_keywords')
                    ->label('Palabras clave')
                    ->placeholder('óleo, taller de pintura, Madrid')
                    ->helperText('Separadas por comas.')
                    ->maxLength(255) : null,
                Textarea::make('seo_description')
                    ->label('Descripción SEO')
                    ->placeholder('Aprende a pintar al óleo en grupos de 8 personas en nuestro estudio de Lavapiés. Materiales incluidos.')
                    ->helperText('Texto bajo el título en Google. Entre 120 y 160 caracteres.')
                    ->rows(2)
                    ->maxLength(160)
                    ->columnSpanFull(),
            ])));
    }
}
