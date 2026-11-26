<?php

namespace App\Providers;

use App\View\Composers\SiteComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Datos globales (SEO, Google Ads, menú, datos del estudio) del layout público.
        View::composer('app', SiteComposer::class);

        // Límite de envíos del formulario de contacto por IP (ruta POST /contactos).
        RateLimiter::for('contacto', function (Request $request) {
            return Limit::perMinute(config('contacto.max_por_minuto'))
                ->by($request->ip())
                ->response(fn () => redirect()->to(url()->previous().'#contacto')
                    ->withInput()
                    ->with('contacto_error', 'Has enviado varios mensajes seguidos. Espera un minuto y vuelve a intentarlo.'));
        });
    }
}
