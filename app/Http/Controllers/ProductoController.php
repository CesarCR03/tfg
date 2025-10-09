<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    protected function returnViewWithContext($productos, $currentCollectionId = null)
    {
        // Obtenemos todas las categorías de la BD y las ordenamos por nombre
        $categories = Categoria::orderBy('Nombre')->get();

        // Pasamos todas las variables necesarias
        return view('productos.index', compact('productos', 'currentCollectionId', 'categories'));
    }
    public function showProducts()
    {
        $productos = Producto::with('imagenes')->paginate(12);
        return $this->returnViewWithContext($productos, null);
    }

    public function show($id)
    {
        $producto = Producto::with('imagenes')->findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function porCategoria($idCategoria)
    {
        /*$productos = Producto::with('imagenes')
            ->whereHas('categorias', function ($query) use ($idCategoria) {
                $query->where('Categoria.id_categoria', $idCategoria);
            })
            ->paginate(12);

        return view('productos.index', compact('productos'));*/
        $productos = Producto::with('imagenes')
            ->whereHas('categorias', function ($query) use ($idCategoria) {
                $query->where('Categoria.id_categoria', $idCategoria);
            })
            ->paginate(12);

        // USAR SIEMPRE la función helper que pasa $categories
        return $this->returnViewWithContext($productos, null);
    }

    /**
     * Filtrar productos por colección (corregido el nombre a 'coleccion' para que coincida con la ruta).
     */
    public function coleccion($idColeccion)
    {
        $productos = Producto::with('imagenes')
            ->whereHas('colecciones', function ($query) use ($idColeccion) {
                // Filtra productos donde exista una entrada en la tabla pivote
                // que coincida con el id_coleccion proporcionado.
                $query->where('Coleccion.id_coleccion', $idColeccion);
            })
            ->paginate(12);

        return $this->returnViewWithContext($productos, $idColeccion);
    }

    public function porColeccionYCategoria($idColeccion, $idCategoria)
    {
        $productos = Producto::with('imagenes')
            // Filtrar por Colección
            ->whereHas('colecciones', function ($query) use ($idColeccion) {
                $query->where('Coleccion.id_coleccion', $idColeccion);
            })
            // Filtrar por Categoría
            ->whereHas('categorias', function ($query) use ($idCategoria) {
                $query->where('Categoria.id_categoria', $idCategoria);
            })
            ->paginate(12);

        // Pasamos el ID de la colección para mantener el contexto en la vista
        return $this->returnViewWithContext($productos, $idColeccion);
    }
}
