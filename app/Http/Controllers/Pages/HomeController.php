<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\CMSPaginas\Footer;
use App\Models\CMSPaginas\Header;
use App\Models\Galerias\GaleriaColeccion;
use App\Models\Servicios\Taller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

/**
 * Portada pública. Carga el contenido gestionado desde el panel;
 * cada sección usa sus textos de ejemplo si todavía no hay datos.
 */
class HomeController extends Controller
{
    public function __invoke(): View
    {
        try {
            $datos = [
                'header' => Header::current(),
                'footer' => Footer::current(),
                'talleresPublicados' => Taller::query()->publicado()->ordenado()->get(),
                'colecciones' => GaleriaColeccion::query()
                    ->publicado()
                    ->ordenado()
                    ->with('elementosVisibles')
                    ->get(),
            ];
        } catch (\Throwable $e) {
            // Si la base de datos falla, la web se muestra con el contenido de ejemplo.
            Log::error('No se pudo cargar el contenido de la portada', ['error' => $e->getMessage()]);

            $datos = ['header' => null, 'footer' => null, 'talleresPublicados' => collect(), 'colecciones' => collect()];
        }

        return view('ezraStudio.home', $datos + ['seoKey' => 'home']);
    }
}
