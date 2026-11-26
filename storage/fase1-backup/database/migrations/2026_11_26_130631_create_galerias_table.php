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
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Galeria');
            $table->string('description')->default('Galeria description');

            $table->string('image')->default('');
            $table->string('video')->default('');
            $table->string('pdf')->default('');

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
        Schema::dropIfExists('galerias');
    }
};
