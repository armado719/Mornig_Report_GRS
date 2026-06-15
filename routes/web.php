<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\RigController;
use App\Http\Controllers\Admin\PozoController;

// Redirige raíz al login o dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reportes — accesible para ADMIN y RIG_MANAGER
    Route::middleware(['role:ADMIN|RIG_MANAGER'])->prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/',            [ReporteController::class, 'index'])->name('index');
        Route::get('/nuevo',       [ReporteController::class, 'crear'])->name('crear');
        Route::get('/{id}',        [ReporteController::class, 'ver'])->name('ver');
        Route::get('/{id}/editar', [ReporteController::class, 'editar'])->name('editar');
        Route::get('/{id}/pdf',    [ReporteController::class, 'pdf'])->name('pdf');
        Route::get('/{id}/excel',  [ReporteController::class, 'excel'])->name('excel');
    });

    // Administración — solo ADMIN
    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('usuarios', UsuarioController::class)->names('usuarios');
        Route::resource('rigs',     RigController::class)->names('rigs');
        Route::resource('pozos',    PozoController::class)->names('pozos');
    });

});

require __DIR__.'/auth.php';
