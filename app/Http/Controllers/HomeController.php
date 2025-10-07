<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Coleccion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio con categorías y productos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cargar todas las categorías
        $categorias = Categoria::all();

        // Cargar productos en stock (puedes ajustar el filtro o el número)
        $productos = Producto::where('Stock', '>', 0)
            ->with('imagenes')   // precargar imágenes
            ->take(6)            // mostrar solo 6
            ->get();

        // Devolver la vista 'home' pasándole los datos
        return view('home', compact('categorias', 'productos'));
    }
}

