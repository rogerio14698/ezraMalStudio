<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enlaces de navegación (menú principal y menú del pie).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navs', function (Blueprint $table) {
            $table->id();
            $table->string('label', 60);
            // Ancla (#talleres), ruta interna (/galeria) o URL externa.
            $table->string('url');
            // 'header' o 'footer'.
            $table->string('location', 20)->default('header');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['location', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navs');
    }
};
