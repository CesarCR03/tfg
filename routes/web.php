<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/productos', [ProductoController::class, 'showProducts'])->name('tienda');
Route::get('/coleccion/{id}', [ProductoController::class, 'coleccion'])->name('coleccion');
Route::get('/categoria/{id}', [ProductoController::class, 'porCategoria'])->name('categoria.show');
Route::get('/coleccion/{idColeccion}/categoria/{idCategoria}',
    [ProductoController::class, 'porColeccionYCategoria'])->name('coleccion.categoria.show');


