<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Colecciones de galería (p. ej. "Óleo 2026", "Taller infantil de verano").
 * Cada colección agrupa N elementos de 'galerias'.
 * Va antes que 'galerias' porque esta tiene la clave foránea.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeria_colecciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();

            $table->string('portada')->nullable();
            $table->json('portada_variants')->nullable();

            $table->unsignedInteger('orden')->default(0);
            // App\Enums\EstadoPublicacion: borrador | publicado
            $table->string('estado', 20)->default('borrador')->index();

            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeria_colecciones');
    }
};
