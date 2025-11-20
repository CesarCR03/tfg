<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Rutas de Productos y Tienda
Route::get('/productos', [ProductoController::class, 'showProducts'])->name('tienda');
Route::get('/coleccion/{id}', [ProductoController::class, 'coleccion'])->name('coleccion');
Route::get('/categoria/{id}', [ProductoController::class, 'porCategoria'])->name('categoria.show');
Route::get('/coleccion/{idColeccion}/categoria/{idCategoria}', [ProductoController::class, 'porColeccionYCategoria'])->name('coleccion.categoria.show');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// 3. Rutas Estáticas
Route::view('/terminos', 'static.terminos')->name('terminos');
Route::view('/politica-cookies', 'static.cookies')->name('politica.cookies');
Route::get('/locations', [HomeController::class, 'locations'])->name('locations');

// 4. Rutas de "Drops"
Route::get('/menu/drops', [HomeController::class, 'drops'])->name('drops.index');
Route::get('/menu/drops/{idColeccion}', [HomeController::class, 'drops'])->name('drops.show');

// 5. Rutas del Carrito
Route::get('/carrito', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/carrito/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/carrito/remove/{idProducto}/{talla}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::patch('/carrito/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/checkout/process', [OrderController::class, 'processOrder'])->name('order.process');

/* Rutas creadas por Breeze */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';


/* Estas son las rutas del panel de admin que creamos */

// 8. Nuestras rutas de Admin (protegidas por el constructor del AdminController)
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/productos', [AdminController::class, 'productosIndex'])->name('productos.index');
        // 1. Mostrar el formulario
        Route::get('/productos/crear', [AdminController::class, 'productoCreate'])->name('productos.create');
        // 2. Guardar los datos (POST)
        Route::post('/productos', [AdminController::class, 'productoStore'])->name('productos.store');
        Route::get('/colecciones/crear', [AdminController::class, 'coleccionCreate'])->name('colecciones.create');
        Route::post('/colecciones', [AdminController::class, 'coleccionStore'])->name('colecciones.store');
    });

