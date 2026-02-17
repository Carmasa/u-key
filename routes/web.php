<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FotoProductoController;

// ✅ RUTA PÚBLICA: Servir imágenes BLOB
Route::get('/img/foto/{foto}', [FotoProductoController::class, 'servir'])->name('foto.servir');

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

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/procesar', [CheckoutController::class, 'procesar'])->name('checkout.procesar');
Route::get('/checkout/exito/{pedido}', [CheckoutController::class, 'exito'])->name('checkout.exito');
Route::get('/checkout/cancelar/{pedido}', [CheckoutController::class, 'cancelar'])->name('checkout.cancelar');

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
    
    // Rutas de Pedidos
    Route::controller(App\Http\Controllers\AdminPedidoController::class)->prefix('pedidos')->name('pedidos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::patch('/{id}/estado', 'updateStatus')->name('update-status');
        Route::get('/{id}/packing-list', 'downloadPackingList')->name('packing-list');
        Route::get('/{id}/shipping-label', 'downloadShippingLabel')->name('shipping-label');
    });
});

// Rutas autenticadas de usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil/pedidos', [App\Http\Controllers\UserPedidoController::class, 'index'])->name('user.pedidos.index');
});
