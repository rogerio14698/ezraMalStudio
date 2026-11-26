<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * php artisan db:seed
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        // Usuario de pruebas (sin acceso al panel) solo en local.
        if (app()->isLocal()) {
            User::factory()->create([
                'name' => 'Ezra test',
                'email' => 'a@a.com',
            ]);
        }
    }
}
