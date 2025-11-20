<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Coleccion;
use App\Models\ProductoStock;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    /**
     * Constructor para proteger todas las rutas de este controlador.
     */
    public function __construct()
    {
        // 1. Requerimos que el usuario haya iniciado sesión (esto lo gestiona Breeze)
        $this->middleware('auth');

        // 2. Comprobamos el ROL aquí mismo, en lugar de en las rutas.
        $this->middleware(function ($request, $next) {

            // Usamos la función isAdmin() que definimos en el modelo User.php
            if (Auth::user() && Auth::user()->isAdmin()) {
                // Si es Admin, le dejamos continuar.
                return $next($request);
            }

            // Si no es Admin, lo redirigimos a la página de inicio con un error.
            return redirect(route('home'))->with('error', 'Acceso denegado. No tienes permisos de administrador.');
        });
    }

    /**
     * Muestra la vista principal (Dashboard) del panel de administrador.
     */
    public function dashboard(): View
    {
        // De momento, solo devolvemos una vista simple.
        // Más adelante aquí pondremos estadísticas, etc.
        return view('admin.dashboard');
    }

    public function productosIndex()
    {
        // Obtenemos todos los productos, paginados de 10 en 10
        // Usamos 'with' para cargar stock e imágenes y evitar muchas consultas
        $productos = Producto::with('tallas', 'imagenes')->orderBy('id_producto', 'desc')->paginate(10);

        return view('admin.productos.index', compact('productos'));
    }

    // --- MUESTRA EL FORMULARIO ---
    public function productoCreate()
    {
        // Necesitamos las categorías y colecciones para el desplegable
        $categorias = Categoria::all();
        $colecciones = Coleccion::all();

        return view('admin.productos.create', compact('categorias', 'colecciones'));
    }

    // --- GUARDA EL PRODUCTO EN LA BD ---
    public function productoStore(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Máx 2MB
            // Validamos el stock de las tallas (pueden ser nulos si no hay stock)
            /*'stock_s' => 'nullable|integer|min:0',
            'stock_m' => 'nullable|integer|min:0',
            'stock_l' => 'nullable|integer|min:0',
            'stock_unica' => 'nullable|integer|min:0',*/
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:Categoria,id_categoria',
            'colecciones' => 'nullable|array',
            'colecciones.*' => 'exists:Coleccion,id_coleccion',
        ]);
        try {
            // 2. Crear el Producto base
            $producto = Producto::create([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Precio' => $request->precio,
            ]);

            // 3. Subir y Guardar la Imagen
            if ($request->hasFile('imagen')) {
                // Guardar en 'public/storage/productos'
                $rutaImagen = $request->file('imagen')->store('productos', 'public');

                // Crear el registro en la tabla Imagen
                Imagen::create([
                    'URL' => $rutaImagen, // Guardamos la ruta relativa (ej: productos/foto.jpg)
                    'producto_id' => $producto->id_producto,
                ]);
            }

            // 4. Guardar el Stock por Tallas (Tabla Producto_stock)
            // Creamos un array para recorrerlo fácil
            $tallas = [
                'S' => $request->stock_s,
                'M' => $request->stock_m,
                'L' => $request->stock_l,
                'Unica' => $request->stock_unica,
            ];

            foreach ($tallas as $nombreTalla => $cantidad) {
                // Solo guardamos si se ha puesto una cantidad mayor que 0
                if ($cantidad !== null && $cantidad > 0) {
                    ProductoStock::create([
                        'id_producto' => $producto->id_producto,
                        'talla' => $nombreTalla,
                        'stock' => $cantidad
                    ]);
                }
            }

            // 5. ASIGNAR CATEGORÍAS Y COLECCIONES (¡NUEVO!)
            if ($request->has('categorias')) {
                // 'categorias' es el nombre de la relación en el Modelo Producto
                $producto->categorias()->sync($request->categorias);
            }

            if ($request->has('colecciones')) {
                // 'colecciones' es el nombre de la relación en el Modelo Producto
                $producto->colecciones()->sync($request->colecciones);
            }

            return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Si falla, deshacemos todo
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    // --- MÉTODOS PARA CREAR COLECCIONES ---

    public function coleccionCreate()
    {
        return view('admin.colecciones.create');
    }

    public function coleccionStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        Coleccion::create([
            'Nombre' => $request->nombre,
            'Año' => $request->anio // Asegúrate que tu columna en la BD se llama 'Año' o 'anio'
        ]);

        return redirect()->route('admin.productos.create')->with('success', 'Colección creada. Ahora puedes seleccionarla.');
    }
}
