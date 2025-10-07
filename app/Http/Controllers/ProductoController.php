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

    /*public function showColeccion(){
        $productos = Producto::with('imagenes')->paginate(12);
        return view('home', compact(''));
    }*/
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

    // Filtrar productos por colección
    public function porColeccion($idColeccion)
    {
        $productos = Producto::with('imagenes')
            ->whereHas('colecciones', function ($query) use ($idColeccion) {
                $query->where('Coleccion.id_coleccion', $idColeccion);
            })
            ->paginate(12);

        return view('productos.index', compact('productos'));
    }

}
