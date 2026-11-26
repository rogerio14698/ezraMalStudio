<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Elementos de galería (imagen, vídeo o PDF) dentro de una colección.
 * Al borrar la colección se borran sus elementos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coleccion_id')
                ->constrained('galeria_colecciones')
                ->cascadeOnDelete();

            // App\Enums\TipoMedia: imagen | video | pdf
            $table->string('tipo', 10)->default('imagen');
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('image')->nullable();
            $table->json('image_variants')->nullable();
            $table->string('alt_text')->nullable();

            $table->string('video')->nullable();
            $table->string('video_poster')->nullable();
            $table->json('video_poster_variants')->nullable();

            $table->string('pdf')->nullable();
            $table->unsignedBigInteger('pdf_size')->nullable();

            // Posición dentro de la colección (reordenable en Filament).
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['coleccion_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galerias');
    }
};
