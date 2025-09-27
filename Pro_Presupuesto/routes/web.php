<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::view('/', 'welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas solo para admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('productos', ProductoController::class);
});

// Rutas accesibles para todos los usuarios logueados
Route::middleware(['auth'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('presupuestos', PresupuestoController::class);
});


Route::middleware('auth')->group(function () {
    // PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD DE CLIENTES
    Route::resource('clientes', ClienteController::class);
    
    // CRUD DE PRODUCTOS
    Route::resource('productos', ProductoController::class);

    //CRUD DE PRESUPUESTO
    Route::resource('presupuestos', PresupuestoController::class);

    // PRESUPUESTOS
    Route::get('/presupuestos', [PresupuestoController::class, 'index'])->name('presupuestos.index');
    Route::get('/presupuestos/create', [PresupuestoController::class, 'create'])->name('presupuestos.create');
    Route::post('/presupuestos', [PresupuestoController::class, 'store'])->name('presupuestos.store');
});

require __DIR__.'/auth.php';
