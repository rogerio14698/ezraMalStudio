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
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('ExitStudio');
            $table->string('description')->default('ExitStudio description');

            $table->string('phone')->default('');
            $table->string('email')->default('');
            $table->string('address')->default('');

            $table->string('googleMaps')->default('');
            $table->string('redes')->default('');
            $table->string('copyright')->default('© 2025 ExitStudio. All rights reserved.');

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
        Schema::dropIfExists('footers');
    }
};
