<?php

namespace App\Models\SEO;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Ajustes globales de SEO y Google Ads (una sola fila).
 * Uso en el layout: $seo = SeoSetting::current();
 *
 * Los getters *Id() validan el formato para que un valor mal escrito en el
 * panel nunca acabe inyectado en el <head>.
 */
class SeoSetting extends Model
{
    private const CACHE_KEY = 'seo_settings.current';

    public const DEFAULT_UTM_PARAMETERS = [
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid',
    ];

    protected $table = 'seo_settings';

    protected $fillable = [
        'site_name',
        'title_separator',
        'default_title',
        'default_description',
        'default_keywords',
        'default_og_image',
        'robots',
        'google_site_verification',
        'google_ads_enabled',
        'google_ads_conversion_id',
        'google_ads_conversion_labels',
        'google_analytics_id',
        'head_scripts',
        'body_scripts',
        'utm_tracking_enabled',
        'utm_parameters',
    ];

    protected function casts(): array
    {
        return [
            'google_ads_enabled' => 'boolean',
            'google_ads_conversion_labels' => 'array',
            'utm_tracking_enabled' => 'boolean',
            'utm_parameters' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // Cualquier cambio desde el panel invalida la caché.
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Fila de ajustes (cacheada). Si aún no existe, devuelve una instancia
     * con los valores por defecto sin guardarla.
     */
    public static function current(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->first())
            ?? new static(['site_name' => config('app.name')]);
    }

    /**
     * "Óleo para principiantes" -> "Óleo para principiantes · ExitStudio".
     */
    public function fullTitle(?string $title = null): string
    {
        $title = $title ?: $this->default_title;

        return $title
            ? "{$title} {$this->title_separator} {$this->site_name}"
            : (string) $this->site_name;
    }

    public function googleAdsConversionId(): ?string
    {
        $id = trim((string) $this->google_ads_conversion_id);

        return $this->google_ads_enabled && preg_match('/^AW-\d{6,15}$/', $id) ? $id : null;
    }

    /**
     * Valor send_to para gtag('event', 'conversion', ...): "AW-123456789/AbC-D_efG".
     */
    public function conversionSendTo(string $evento): ?string
    {
        $id = $this->googleAdsConversionId();

        $label = collect($this->google_ads_conversion_labels ?? [])
            ->firstWhere('evento', $evento)['label'] ?? null;

        return $id && $label && preg_match('/^[\w-]{4,64}$/', $label) ? "{$id}/{$label}" : null;
    }

    public function googleAnalyticsId(): ?string
    {
        $id = trim((string) $this->google_analytics_id);

        return preg_match('/^G-[A-Z0-9]{4,20}$/', $id) ? $id : null;
    }

    /**
     * Parámetros de campaña que se conservan (vacío si el seguimiento está desactivado).
     *
     * @return list<string>
     */
    public function trackedParameters(): array
    {
        if (! $this->utm_tracking_enabled) {
            return [];
        }

        return $this->utm_parameters ?: self::DEFAULT_UTM_PARAMETERS;
    }
}
