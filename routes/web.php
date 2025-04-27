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

// Ruta para listar todas las gráficas
Route::get('/graficadores/lista', [GraficadorController::class, 'listatodaslasgraficas']);
// Ruta para añadir colaborador
Route::post('/graficador/colaborador', [GraficadorController::class, 'addCollaborator']);
Route::get('/graficador/portal', [GraficadorController::class, 'PortalGraficador']);


require __DIR__.'/auth.php';
