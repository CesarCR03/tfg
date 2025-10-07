<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function showProducts()
    {
        // Asegúrate de que tu carpeta de views sea resources/views/productos
        $productos = Producto::with('imagenes')->paginate(12);
        return view('productos.index', compact('productos'));
    }

    public function show($id)
    {
        $producto = Producto::with('imagenes')->findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function porCategoria($idCategoria)
    {
        $productos = Producto::with('imagenes')
            ->whereHas('categorias', function ($query) use ($idCategoria) {
                $query->where('Categoria.id_categoria', $idCategoria);
            })
            ->paginate(12);

        return view('productos.index', compact('productos'));
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

        return view('productos.index', compact('productos'));
    }
}
