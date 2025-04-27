<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraficadorController;

Route::resource('graficadores', GraficadorController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route::get('/usuarios-graficadores', [GraficadorController::class, 'listarUsuariosGraficadores'])->name('usuarios.graficadores');

Route::get('/usuarios-graficadores/{idGraficador}/usuarios', [GraficadorController::class, 'listarUsuariosPorGraficador'])->name('graficador.usuarios');

Route::post('/graficadorusuario/{idGraficador}/{id}/cambiar-estado', [GraficadorController::class, 'cambiarEstado'])
    ->name('graficadorusuario.cambiarEstado');

require __DIR__.'/auth.php';
