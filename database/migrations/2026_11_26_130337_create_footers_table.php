<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pie de página: datos de contacto, redes y horario.
 * Tabla de una sola fila (se edita desde Filament).
 * El SEO global ya no vive aquí: ver seo_settings.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('ExitStudio');
            $table->text('description')->nullable();

            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->text('google_maps_url')->nullable();

            // [{"nombre": "Instagram", "url": "https://instagram.com/..."}]
            $table->json('redes')->nullable();
            // [{"dia": "Martes y jueves", "horas": "17:00 – 20:30"}]
            $table->json('horario')->nullable();

            $table->string('copyright')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footers');
    }
};
