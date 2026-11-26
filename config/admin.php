<?php

/*
|--------------------------------------------------------------------------
| Panel de administración (Filament)
|--------------------------------------------------------------------------
| - Producción: FILAMENT_DOMAIN=admin.exitstudio.es -> el panel vive en la raíz
|   del subdominio (https://admin.exitstudio.es).
| - Local: deja FILAMENT_DOMAIN vacío -> el panel vive en /{FILAMENT_PATH}
|   (por defecto http://127.0.0.1:8000/admin).
*/

return [

    'domain' => env('FILAMENT_DOMAIN') ?: null,

    'path' => env('FILAMENT_PATH', 'admin'),

    // Datos del primer administrador, usados por AdminUserSeeder.
    'seed' => [
        'name' => env('ADMIN_NAME', 'Administrador'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
