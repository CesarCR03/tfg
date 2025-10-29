<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CartController;
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/productos', [ProductoController::class, 'showProducts'])->name('tienda');
Route::get('/coleccion/{id}', [ProductoController::class, 'coleccion'])->name('coleccion');
Route::get('/categoria/{id}', [ProductoController::class, 'porCategoria'])->name('categoria.show');
Route::get('/coleccion/{idColeccion}/categoria/{idCategoria}',
    [ProductoController::class, 'porColeccionYCategoria'])->name('coleccion.categoria.show');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

Route::view('/terminos', 'static.terminos')->name('terminos');
Route::view('/politica-cookies', 'static.cookies')->name('politica.cookies');
// Rutas para el Carrito
// GET: Muestra el contenido del carrito
Route::get('/carrito', [CartController::class, 'showCart'])->name('cart.show');

// POST: Recibe los datos del formulario (producto, cantidad, talla) para añadir al carrito
Route::post('/carrito/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/carrito/remove/{idProducto}/{talla}', [CartController::class, 'removeFromCart'])->name('cart.remove');
// PATCH: Actualiza la cantidad de un producto específico en la cesta
Route::patch('/carrito/update', [CartController::class, 'updateCart'])->name('cart.update');
