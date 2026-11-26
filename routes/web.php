<?php

/*
|--------------------------------------------------------------------------
| Rutas públicas de la web
|--------------------------------------------------------------------------
| El panel de administración (/admin en local, admin.exitstudio.es en
| producción) NO está aquí: lo registra Filament desde
| app/Providers/Filament/AdminPanelProvider.php (dominio y ruta en config/admin.php).
| Para ver todas las rutas: php artisan route:list
*/

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Pages\HomeController;
use Illuminate\Support\Facades\Route;

// Portada (vistas en resources/views/ezraStudio).
Route::get('/', HomeController::class)->name('home');

// Formulario de contacto del pie. Límite por IP definido en AppServiceProvider ("contacto").
Route::post('/contactos', [ContactController::class, 'store'])
    ->middleware('throttle:contacto')
    ->name('contacto.store');
