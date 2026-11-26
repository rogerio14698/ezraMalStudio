<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Actividades puntuales (masterclass, salidas, eventos). Misma estructura que 'tallers'.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            // App\Enums\Publico: adultos | infancias | todos
            $table->string('publico', 20)->default('todos');
            $table->string('summary', 255)->nullable();
            $table->longText('description')->nullable();

            $table->string('image')->nullable();
            $table->json('image_variants')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('video')->nullable();
            $table->string('video_poster')->nullable();
            $table->json('video_poster_variants')->nullable();
            $table->string('pdf')->nullable();
            $table->unsignedBigInteger('pdf_size')->nullable();

            $table->decimal('price', 8, 2)->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->string('horario')->nullable();
            $table->string('duration')->nullable();
            $table->string('level')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedSmallInteger('plazas')->nullable();

            $table->string('status', 20)->default('borrador')->index();
            $table->unsignedInteger('orden')->default(0);

            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
