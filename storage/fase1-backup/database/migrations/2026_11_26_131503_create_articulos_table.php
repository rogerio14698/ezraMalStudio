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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Articulo');
            $table->longText('article')->default('Articulo text');
            $table->string('image')->default('');
            $table->string('video')->nullable();
            $table->string('pdf')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->date('date_hour')->default('');
            $table->string('status')->default('active');
            
            $table->string('author')->nullable();
            $table->string('fuentes')->nullable();

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
        Schema::dropIfExists('articulos');
    }
};
