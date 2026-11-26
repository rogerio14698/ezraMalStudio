<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tallers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Taller');
            $table->string('description')->default('Taller description');
            $table->string('image')->default('');
            $table->string('video')->default('');
            $table->string('pdf')->default('');
            $table->decimal('price', 10, 2)->default(0);
            $table->date('date_hour')->default('');
            //puede ser horas o semanas
            $table->string('duration')->default('');
            $table->string('level')->default('');
            $table->string('category')->default('');
            $table->string('tags')->default('');
            $table->string('status')->default('active');
            $table->integer('plazas')->default(0);

            $table->string('SEO_title')->default('');
            $table->string('SEO_description')->default('');
            $table->string('SEO_keywords')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tallers');
    }
};
