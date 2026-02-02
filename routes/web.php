<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;

// Catalogo routes
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/categoria/{slug}', [CatalogoController::class, 'porCategoria'])->name('catalogo.categoria');
Route::get('/producto/{id}', [CatalogoController::class, 'detalle'])->name('catalogo.detalle');

// Carrito routes
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::patch('/carrito/{carrito}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/{carrito}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::delete('/carrito', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
Route::get('/carrito/contador', [CarritoController::class, 'contador'])->name('carrito.contador');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes - Protegidas con auth y isAdmin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::delete('fotos/{foto}', [App\Http\Controllers\FotoProductoController::class, 'destroy'])->name('fotos.destroy');
});
