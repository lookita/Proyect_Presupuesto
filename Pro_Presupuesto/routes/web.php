<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;

//PAGINA PRINCIPAL
Route::get('/', function () {
    return view('welcome');
});

//DASHBOARD (REQUIERE AUTENTICACION Y VERIFICACION))
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

//RUTAS PROTEGIDAS POR AUTENTICACION 
Route::middleware('auth')->group(function () {

    //PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



//CRUD DE CLIENTES
Route::resource('clientes', ClienteController::class);


require __DIR__.'/auth.php';


//FRANCOO
/*
Configuración de rutas para ClienteController


Ya están listos el modelo Cliente y el request ClienteStoreRequest.

Registrar las rutas en routes/web.php usando:
    Route::resource('clientes', ClienteController::class);

Esto habilita: index, create, store, edit, update, destroy.

Si no se usa show():
    Route::resource('clientes', ClienteController::class)->except(['show']);

Acordate de incluirlo dentro del grupo protegido por auth.

Avísame cuando esté listo así seguimos con la integración de vistas y validaciones.
*/