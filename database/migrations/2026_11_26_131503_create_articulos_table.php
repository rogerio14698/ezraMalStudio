<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Artículos del blog / noticias del estudio.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();

            $table->string('image')->nullable();
            $table->json('image_variants')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('video')->nullable();
            $table->string('video_poster')->nullable();
            $table->json('video_poster_variants')->nullable();
            $table->string('pdf')->nullable();
            $table->unsignedBigInteger('pdf_size')->nullable();

            $table->dateTime('published_at')->nullable()->index();
            // App\Enums\EstadoPublicacion: borrador | publicado
            $table->string('status', 20)->default('borrador')->index();
            $table->string('author')->nullable();
            $table->text('fuentes')->nullable();

            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
