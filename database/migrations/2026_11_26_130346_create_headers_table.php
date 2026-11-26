<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cabecera / hero de la portada. Tabla de una sola fila.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('headers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('ExitStudio');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // Imagen de fondo/hero + variantes generadas por MediaOptimizationService.
            $table->string('background')->nullable();
            $table->json('background_variants')->nullable();
            $table->string('background_alt')->nullable();

            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('headers');
    }
};
