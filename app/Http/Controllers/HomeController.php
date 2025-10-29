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
    public function locations()
    {
        return view('locations');
    }
    /**
     * Muestra el escaparate de Drops, cargando los productos de la colección activa.
     */
    public function drops($idColeccion = null)
    {
        // 1. Obtener todas las colecciones para el menú lateral
        $allCollections = Coleccion::orderBy('id_coleccion', 'desc')->get();

        // 2. Determinar la colección a mostrar (o la última si no se especifica)
        // Usamos optional() para evitar errores si no hay colecciones
        $currentCollectionId = $idColeccion ?? optional($allCollections->first())->id_coleccion;

        $selectedCollection = null;
        $productos = collect([]); // Colección vacía por defecto

        if ($currentCollectionId) {
            // Cargar la colección con sus productos e imágenes
            $selectedCollection = Coleccion::with([
                'productos' => function ($query) {
                    $query->with('imagenes');
                }
            ])->find($currentCollectionId);

            // Extraer los productos para la galería
            $productos = $selectedCollection ? $selectedCollection->productos : collect([]);
        }

        return view('drops', compact('allCollections', 'productos', 'currentCollectionId', 'selectedCollection'));
    }
}

