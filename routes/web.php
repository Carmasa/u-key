<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;

// Catalogo routes
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/categoria/{slug}', [CatalogoController::class, 'porCategoria'])->name('catalogo.categoria');
Route::get('/producto/{id}', [CatalogoController::class, 'detalle'])->name('catalogo.detalle');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes - Protegidas con auth y isAdmin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);
});
