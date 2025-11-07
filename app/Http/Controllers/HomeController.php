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
        $productos = Producto::whereHas('tallas', function ($query) {
            $query->where('stock', '>', 0);
        })
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
        $allCollections = Coleccion::orderBy('id_coleccion', 'desc')->get();

        $currentCollectionId = $idColeccion ?? optional($allCollections->first())->id_coleccion;

        $selectedCollection = null;
        $imagenes = collect([]); // Usaremos $imagenes en lugar de $productos

        if ($currentCollectionId) {
            // CAMBIO DE LÓGICA: Cargar la relación 'imagenes()'
            $selectedCollection = Coleccion::with('imagenes')->find($currentCollectionId);

            // Extraer las imágenes para la galería
            $imagenes = $selectedCollection ? $selectedCollection->imagenes : collect([]);
        }

        // Pasamos $imagenes a la vista
        return view('drops', compact('allCollections', 'imagenes', 'currentCollectionId', 'selectedCollection'));
    }
}

