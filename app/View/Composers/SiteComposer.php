<?php

namespace App\View\Composers;

use App\Filament\Resources\FooterResource;
use App\Models\CMSPaginas\Footer;
use App\Models\CMSPaginas\Nav;
use App\Models\SEO\SeoPage;
use App\Models\SEO\SeoSetting;
use App\Support\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Datos globales del layout público (app.blade.php):
 *  - $estudio:  nombre, email y redes (panel → Pie de página).
 *  - $menu:     enlaces del menú principal (panel → Menús).
 *  - $meta:     título, descripción, robots, canonical y Open Graph.
 *  - $tracking: IDs de Google Ads/Analytics ya validados y scripts personalizados.
 *
 * Cada vista indica qué "SEO por página" usar pasando $seoKey (p. ej. 'home').
 * Si la base de datos falla, se usan valores por defecto y la web sigue funcionando.
 */
class SiteComposer
{
    private const MENU_DEMO = [
        ['texto' => 'Visítanos', 'href' => '#visita', 'nueva' => false],
        ['texto' => 'Talleres', 'href' => '#talleres', 'nueva' => false],
        ['texto' => 'Galería', 'href' => '#galeria', 'nueva' => false],
        ['texto' => 'Manifiesto', 'href' => '#manifiesto', 'nueva' => false],
    ];

    public function compose(View $view): void
    {
        try {
            $seo = SeoSetting::current();
            $footer = Footer::current();
            $pagina = SeoPage::forKey($view->getData()['seoKey'] ?? 'home');
            $menu = Nav::query()->menu('header')->get();
        } catch (\Throwable $e) {
            Log::error('No se pudieron cargar los datos globales del layout', ['error' => $e->getMessage()]);
            $seo = new SeoSetting(['site_name' => config('app.name')]);
            $footer = $pagina = null;
            $menu = collect();
        }

        $view->with([
            'estudio' => $this->estudio($footer),
            'menu' => $menu->isEmpty() ? self::MENU_DEMO : $menu->map(fn (Nav $nav) => [
                'texto' => $nav->label,
                'href' => $nav->url,
                'nueva' => $nav->open_in_new_tab,
            ])->all(),
            'meta' => $this->meta($seo, $pagina),
            'tracking' => [
                'ads_id' => $seo->googleAdsConversionId(),
                'ga_id' => $seo->googleAnalyticsId(),
                'conversion' => session('contacto_ok') ? $seo->conversionSendTo('contacto_enviado') : null,
                'verification' => $seo->google_site_verification,
                'head' => $seo->head_scripts,
                'body' => $seo->body_scripts,
            ],
        ]);
    }

    private function estudio(?Footer $footer): array
    {
        $redes = collect($footer?->redes ?? [])
            ->filter(fn ($red) => filled($red['url'] ?? null))
            ->map(fn ($red) => [
                'nombre' => FooterResource::REDES[$red['red'] ?? ''] ?? ucfirst((string) ($red['red'] ?? 'Red')),
                'url' => $red['url'],
            ])
            ->values()
            ->all();

        return [
            'nombre' => $footer?->title ?: config('app.name'),
            'lema' => $footer?->description ?: 'Taller de arte y creación',
            'email' => $footer?->email ?: config('mail.from.address'),
            'telefono' => $footer?->phone,
            'copyright' => $footer?->copyright,
            'redes' => $redes ?: [
                ['nombre' => 'Instagram', 'url' => '#'],
                ['nombre' => 'TikTok', 'url' => '#'],
            ],
        ];
    }

    private function meta(SeoSetting $seo, ?SeoPage $pagina): array
    {
        return [
            'title' => $seo->fullTitle($pagina?->meta_title),
            'description' => $pagina?->meta_description ?: $seo->default_description
                ?: 'Estudio de artes plásticas: óleo, acuarela, acrílico y técnica mixta para adultos e infancias.',
            'keywords' => $pagina?->meta_keywords ?: $seo->default_keywords,
            'robots' => $pagina && ! $pagina->is_indexable ? 'noindex, follow' : ($seo->robots ?: 'index, follow'),
            'canonical' => $pagina?->canonical_url ?: url()->current(),
            'og_image' => Media::url($pagina?->og_image ?: $seo->default_og_image) ?? asset('images/og.jpg'),
        ];
    }
}
