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
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user() && Auth::user()->isAdmin()) {
                return $next($request);
            }
            return redirect(route('home'))->with('error', 'Acceso denegado.');
        });
    }

    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    public function productosIndex()
    {
        $productos = Producto::with('tallas', 'imagenes')
            ->orderBy('id_producto', 'desc')
            ->paginate(10);

        return view('admin.productos.index', compact('productos'));
    }

    public function productoCreate()
    {
        $categorias = Categoria::all();
        $colecciones = Coleccion::all();
        return view('admin.productos.create', compact('categorias', 'colecciones'));
    }

    public function productoStore(Request $request)
    {
        // 1. Validación (CAMBIO: IMAGEN AHORA ES NULLABLE)
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // <--- AHORA ES OPCIONAL
            'stock_s' => 'nullable|integer|min:0',
            'stock_m' => 'nullable|integer|min:0',
            'stock_l' => 'nullable|integer|min:0',
            'stock_unica' => 'nullable|integer|min:0',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:Categoria,id_categoria',
            'colecciones' => 'nullable|array',
            'colecciones.*' => 'exists:Coleccion,id_coleccion',
        ]);

        try {
            DB::beginTransaction();

            // 2. Crear Producto
            $producto = Producto::create([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Precio' => $request->precio,
            ]);

            // 3. Guardar Imagen (SOLO SI SE SUBE)
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '', $archivo->getClientOriginalName());
                $nombreArchivo = time() . '_' . $nombreLimpio;

                $archivo->move(public_path('storage/productos'), $nombreArchivo);

                Imagen::create([
                    'URL' => 'productos/' . $nombreArchivo,
                    'producto_id' => $producto->id_producto,
                ]);
            }

            // 4. Guardar Stock
            $tallas = [
                'S' => $request->stock_s,
                'M' => $request->stock_m,
                'L' => $request->stock_l,
                'unica' => $request->stock_unica,
            ];
            foreach ($tallas as $nombreTalla => $cantidad) {
                if ($cantidad !== null && $cantidad > 0) {
                    ProductoStock::create([
                        'id_producto' => $producto->id_producto,
                        'talla' => $nombreTalla,
                        'stock' => $cantidad
                    ]);
                }
            }

            // 5. Relaciones
            if ($request->has('categorias')) {
                $producto->categorias()->sync($request->categorias);
            }
            if ($request->has('colecciones')) {
                $producto->colecciones()->sync($request->colecciones);
            }

            DB::commit();

            return redirect('/admin/productos')->with('success', 'Producto creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error técnico: ' . $e->getMessage());
        }
    }

    public function coleccionCreate()
    {
        return view('admin.colecciones.create');
    }

    public function coleccionStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'imagen_coleccion' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $rutaImagen = null;

        if ($request->hasFile('imagen_coleccion')) {
            $archivo = $request->file('imagen_coleccion');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '', $archivo->getClientOriginalName());
            $nombreArchivo = time() . '_' . $nombreLimpio;
            $archivo->move(public_path('storage/colecciones'), $nombreArchivo);
            $rutaImagen = 'colecciones/' . $nombreArchivo;
        }

        $ultimoId = Coleccion::max('id_coleccion');
        $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

        Coleccion::create([
            'id_coleccion' => $nuevoId,
            'Nombre' => $request->nombre,
            'Año' => $request->anio,
            'imagen_url' => $rutaImagen,
        ]);

        return redirect()->route('admin.productos.create')->with('success', 'Colección creada correctamente.');
    }
}
