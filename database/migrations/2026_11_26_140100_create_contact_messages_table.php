<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mensajes del formulario público POST /contactos.
 * La fecha de envío es created_at.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('subject', 150)->nullable();
            $table->text('message');

            // App\Enums\EstadoContacto: no_leido | leido | respondido
            $table->string('status', 20)->default('no_leido')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();

            // Trazabilidad / antispam (IPv6 cabe en 45 caracteres).
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
