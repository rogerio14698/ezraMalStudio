<?php

namespace Tests\Feature;

use App\Mail\NuevoMensajeContacto;
use App\Models\Contacto\ContactMessage;
use App\Models\Galerias\Galeria;
use App\Models\Galerias\GaleriaColeccion;
use App\Models\SEO\SeoSetting;
use App\Models\Servicios\Taller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Web pública: portada con contenido del panel, SEO/Google Ads y formulario de contacto.
 */
class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    private function contacto(array $cambios = []): array
    {
        return array_merge([
            'name' => 'Lucía García',
            'email' => 'lucia@example.com',
            'phone' => '+34 600 123 456',
            'subject' => 'Clase de prueba',
            'message' => 'Me gustaría probar una clase de acuarela.',
            'privacy' => '1',
            'website' => '',
            '_ts' => Crypt::encryptString((string) now()->subSeconds(10)->timestamp),
        ], $cambios);
    }

    public function test_la_portada_usa_el_contenido_de_ejemplo_si_el_panel_esta_vacio(): void
    {
        $this->get('/')->assertOk()->assertSee('Óleo y veladuras')->assertSee('contacto-website', false);
    }

    public function test_la_portada_muestra_talleres_y_galeria_del_panel(): void
    {
        Taller::create(['title' => 'Taller de prueba al óleo', 'publico' => 'adultos', 'status' => 'publicado']);
        Taller::create(['title' => 'Taller en borrador', 'publico' => 'adultos', 'status' => 'borrador']);

        $coleccion = GaleriaColeccion::create(['titulo' => 'Óleo 2026', 'estado' => 'publicado']);
        Galeria::create(['coleccion_id' => $coleccion->id, 'tipo' => 'pdf', 'title' => 'Programa 2027', 'pdf' => 'galeria/pdf/programa.pdf']);
        Galeria::create(['coleccion_id' => $coleccion->id, 'tipo' => 'video', 'title' => 'Proceso', 'video' => 'galeria/videos/proceso.mp4']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Taller de prueba al óleo')
            ->assertDontSee('Taller en borrador')
            ->assertDontSee('Óleo y veladuras')
            ->assertSee('Programa 2027')
            ->assertSee('preload="none"', false);
    }

    public function test_el_layout_inyecta_seo_y_google_ads_validados(): void
    {
        SeoSetting::create([
            'site_name' => 'ExitStudio',
            'default_title' => 'Estudio de pintura',
            'google_ads_enabled' => true,
            'google_ads_conversion_id' => 'AW-123456789',
            'google_ads_conversion_labels' => [['evento' => 'contacto_enviado', 'label' => 'AbC-D_efG123']],
            'google_analytics_id' => 'G-ABC1234',
        ]);

        $this->get('/')
            ->assertSee('<title>Estudio de pintura · ExitStudio</title>', false)
            ->assertSee('googletagmanager.com/gtag/js?id=G-ABC1234', false)
            ->assertDontSee('AW-123456789\/AbC-D_efG123', false);

        // Tras enviar el formulario se dispara la conversión.
        $this->withSession(['contacto_ok' => 'ok'])->get('/')->assertSee('AW-123456789\/AbC-D_efG123', false);
    }

    public function test_un_mensaje_valido_se_guarda_y_avisa_por_email(): void
    {
        Mail::fake();
        config(['contacto.mail_to' => 'hola@exitstudio.es']);

        $this->from('/')->post('/contactos', $this->contacto())
            ->assertRedirectContains('#contacto')
            ->assertSessionHas('contacto_ok');

        $mensaje = ContactMessage::sole();
        $this->assertSame('lucia@example.com', $mensaje->email);
        $this->assertNotNull($mensaje->ip_address);
        Mail::assertSent(NuevoMensajeContacto::class);
    }

    public function test_el_antispam_rechaza_bots(): void
    {
        $this->from('/')->post('/contactos', $this->contacto(['website' => 'http://spam.example']))
            ->assertSessionHasErrorsIn('contacto', 'website');

        $this->from('/')->post('/contactos', $this->contacto(['_ts' => Crypt::encryptString((string) now()->timestamp)]))
            ->assertSessionHasErrorsIn('contacto', 'message');

        $this->from('/')->post('/contactos', $this->contacto(['_ts' => 'manipulado']))
            ->assertSessionHasErrorsIn('contacto', 'message');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_limita_los_envios_por_ip(): void
    {
        config(['contacto.max_por_minuto' => 2]);

        $this->from('/')->post('/contactos', $this->contacto());
        $this->from('/')->post('/contactos', $this->contacto());
        $this->from('/')->post('/contactos', $this->contacto())->assertSessionHas('contacto_error');

        $this->assertSame(2, ContactMessage::count());
    }
}
