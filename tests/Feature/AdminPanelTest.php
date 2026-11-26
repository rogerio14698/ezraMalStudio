<?php

namespace Tests\Feature;

use App\Enums\EstadoContacto;
use App\Filament\Pages\AjustesSeo;
use App\Filament\Resources;
use App\Filament\Resources\GaleriaColeccionResource\Pages\EditGaleriaColeccion;
use App\Filament\Resources\GaleriaColeccionResource\RelationManagers\ElementosRelationManager;
use App\Models\Contacto\ContactMessage;
use App\Models\Galerias\Galeria;
use App\Models\Galerias\GaleriaColeccion;
use App\Models\SEO\SeoSetting;
use App\Models\User;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Comprueba el panel Filament: acceso, que cada pantalla carga,
 * la subida/optimización de imágenes de la galería y los ajustes SEO.
 */
class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['is_admin' => true])->save();

        return $user;
    }

    public function test_un_usuario_normal_no_entra_al_panel(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(Resources\TallerResource::getUrl())
            ->assertForbidden();
    }

    public function test_todas_las_pantallas_cargan(): void
    {
        $this->actingAs($this->admin());

        $resources = [
            Resources\TallerResource::class,
            Resources\ActividadResource::class,
            Resources\ArticuloResource::class,
            Resources\GaleriaColeccionResource::class,
            Resources\HeaderResource::class,
            Resources\FooterResource::class,
            Resources\NavResource::class,
            Resources\SeoPageResource::class,
            Resources\ContactMessageResource::class,
        ];

        foreach ($resources as $resource) {
            $this->get($resource::getUrl('index'))->assertOk();

            if ($resource::hasPage('create')) {
                $this->get($resource::getUrl('create'))->assertOk();
            }
        }

        $coleccion = GaleriaColeccion::create(['titulo' => 'Óleo 2026']);
        $this->get(Resources\GaleriaColeccionResource::getUrl('edit', ['record' => $coleccion]))->assertOk();
        $this->get(AjustesSeo::getUrl())->assertOk();
    }

    public function test_subir_imagen_a_una_coleccion_genera_variantes(): void
    {
        Storage::fake(config('media.disk'));
        $this->actingAs($this->admin());

        $coleccion = GaleriaColeccion::create(['titulo' => 'Óleo 2026']);

        Livewire::test(ElementosRelationManager::class, [
            'ownerRecord' => $coleccion,
            'pageClass' => EditGaleriaColeccion::class,
        ])
            ->callTableAction(CreateAction::class, data: [
                'tipo' => 'imagen',
                'alt_text' => 'Bodegón con granadas',
                'image' => UploadedFile::fake()->image('bodegon.jpg', 2000, 1200),
            ])
            ->assertHasNoTableActionErrors();

        $elemento = Galeria::sole();

        $this->assertSame(1, $elemento->orden);
        $this->assertCount(3, $elemento->image_variants['sources']['webp']);
        Storage::disk(config('media.disk'))->assertExists($elemento->image_variants['sources']['webp'][0]['path']);
    }

    public function test_ajustes_seo_validan_el_id_de_google_ads(): void
    {
        $this->actingAs($this->admin());

        $base = ['site_name' => 'ExitStudio', 'title_separator' => '·', 'robots' => 'index, follow', 'google_ads_enabled' => true];

        Livewire::test(AjustesSeo::class)
            ->fillForm($base + ['google_ads_conversion_id' => 'UA-123'])
            ->call('save')
            ->assertHasFormErrors(['google_ads_conversion_id' => 'regex']);

        Livewire::test(AjustesSeo::class)
            ->fillForm($base + [
                'google_ads_conversion_id' => 'AW-123456789',
                'google_ads_conversion_labels' => [['evento' => 'contacto_enviado', 'label' => 'AbC-D_efG123']],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('AW-123456789/AbC-D_efG123', SeoSetting::current()->conversionSendTo('contacto_enviado'));
    }

    public function test_abrir_un_mensaje_lo_marca_como_leido(): void
    {
        $this->actingAs($this->admin());

        $mensaje = ContactMessage::create(['name' => 'Ana', 'email' => 'ana@example.com', 'message' => 'Hola']);

        $this->get(Resources\ContactMessageResource::getUrl('view', ['record' => $mensaje]))->assertOk();

        $this->assertSame(EstadoContacto::Leido, $mensaje->fresh()->status);
    }
}
