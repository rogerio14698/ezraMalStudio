<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Crea (o actualiza) el administrador del panel Filament.
 *
 *   1. Define en .env: ADMIN_EMAIL, ADMIN_NAME y (opcional) ADMIN_PASSWORD.
 *   2. Ejecuta: php artisan db:seed --class=AdminUserSeeder --force
 *
 * Si no hay ADMIN_PASSWORD se genera una aleatoria y se muestra una sola vez.
 * Volver a ejecutarlo no cambia la contraseña salvo que ADMIN_PASSWORD esté definida.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('admin.seed');

        if (blank($config['email'])) {
            $this->command?->error('Define ADMIN_EMAIL en .env antes de ejecutar AdminUserSeeder.');

            return;
        }

        $user = User::firstOrNew(['email' => $config['email']]);
        $generatedPassword = null;

        if (filled($config['password'])) {
            $user->password = $config['password'];
        } elseif (! $user->exists) {
            $generatedPassword = Str::password(16);
            $user->password = $generatedPassword;
        }

        $user->forceFill([
            'name' => $config['name'],
            'is_admin' => true,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->command?->info("Administrador listo: {$user->email}");

        if ($generatedPassword) {
            $this->command?->warn("Contraseña generada (guárdala, no se volverá a mostrar): {$generatedPassword}");
        }
    }
}
