<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Coleccion;
use Illuminate\Http\Request;
use App\Models\Imagen;
class HomeController extends Controller
{
    /**
     * Muestra la página de inicio con categorías y productos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Cargar datos existentes
        $categorias = Categoria::all();

        // 2. NUEVO: Cargar Colecciones (Ordenadas por las más recientes primero)
        $colecciones = Coleccion::orderBy('id_coleccion', 'asc')->get();

        // 3. Lógica de productos (la que ya tenías para filtrar por stock)
        $productos = Producto::whereHas('tallas', function ($query) {
            $query->where('stock', '>', 0);
        })
            ->with('imagenes')
            ->take(6)
            ->get();

        return view('home', compact('categorias', 'productos', 'colecciones'));
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
        // 1. Cargar todas las colecciones para el menú
        $allCollections = Coleccion::orderBy('id_coleccion', 'desc')->get();

        // 2. Determinar ID actual
        $currentCollectionId = $idColeccion ?: ($allCollections->first() ? $allCollections->first()->id_coleccion : null);

        $selectedCollection = null;
        $imagenes = collect([]);

        if ($currentCollectionId) {
            // 3. Cargar colección y relaciones
            $selectedCollection = Coleccion::with(['imagenes', 'productos.imagenes'])
                ->find($currentCollectionId);

            if ($selectedCollection) {
                // A. Imágenes manuales (Lookbook)
                $lookbookImages = $selectedCollection->imagenes;

                // B. Imágenes de Productos
                $productImages = $selectedCollection->productos->flatMap(function ($producto) {
                    return $producto->imagenes;
                });

                // C. Fusionar y limpiar duplicados
                $imagenes = $lookbookImages->merge($productImages)->unique('id_imagen');

                // 4. (NUEVO) Agregar la PORTADA de la Colección al principio
                if ($selectedCollection->imagen_url) {
                    // Creamos una instancia de Imagen "al vuelo"
                    $portada = new Imagen();
                    $portada->URL = $selectedCollection->imagen_url;

                    // Le damos un ID temporal para que no choque, aunque la vista usa solo la URL
                    $portada->id_imagen = 'cover_' . $selectedCollection->id_coleccion;

                    // La insertamos AL PRINCIPIO de la colección
                    $imagenes->prepend($portada);
                }
            }
        }

        return view('drops', compact('allCollections', 'imagenes', 'currentCollectionId', 'selectedCollection'));
    }
}

