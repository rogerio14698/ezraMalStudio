<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajustes globales de SEO, Google Ads y seguimiento. Tabla de una sola fila
 * (ver SeoSetting::current()).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();

            // Metaetiquetas por defecto (las páginas sin SEO propio heredan estas).
            $table->string('site_name')->default('ExitStudio');
            $table->string('title_separator', 5)->default('·');
            $table->string('default_title', 70)->nullable();
            $table->string('default_description', 160)->nullable();
            $table->string('default_keywords')->nullable();
            $table->string('default_og_image')->nullable();
            $table->string('robots')->default('index, follow');
            $table->string('google_site_verification')->nullable();

            // Google Ads / Analytics.
            $table->boolean('google_ads_enabled')->default(false);
            $table->string('google_ads_conversion_id', 30)->nullable();        // AW-123456789
            // [{"evento": "contacto_enviado", "label": "AbC-D_efG"}]
            $table->json('google_ads_conversion_labels')->nullable();
            $table->string('google_analytics_id', 30)->nullable();             // G-XXXXXXXXXX

            // Scripts adicionales (solo administradores) para <head> y apertura de <body>.
            $table->text('head_scripts')->nullable();
            $table->text('body_scripts')->nullable();

            // UTM / gclid que se conservan en la sesión y se guardan con cada contacto.
            $table->boolean('utm_tracking_enabled')->default(true);
            $table->json('utm_parameters')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
