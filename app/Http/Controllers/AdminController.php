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

    /**
     * Muestra la lista de colecciones (tabla).
     */
    public function coleccionesIndex()
    {
        $colecciones = Coleccion::orderBy('id_coleccion', 'desc')->paginate(10);
        return view('admin.colecciones.index', compact('colecciones'));
    }

    /**
     * Elimina un producto y sus imágenes asociadas.
     */
    public function productoDestroy($id)
    {
        // Buscamos por 'id_producto' explícitamente para evitar errores de clave primaria
        $producto = Producto::with('imagenes')->where('id_producto', $id)->firstOrFail();

        // 1. Borrar imágenes físicas del disco
        foreach ($producto->imagenes as $imagen) {
            // Tu ruta base es storage/ (según tu AdminController anterior)
            $rutaArchivo = public_path('storage/' . $imagen->URL);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
        }

        // 2. Borrar registro (La BD borrará relaciones en cascada)
        $producto->delete();

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    /**
     * Elimina una colección y su imagen de portada.
     */
    public function coleccionDestroy($id)
    {
        $coleccion = Coleccion::where('id_coleccion', $id)->firstOrFail();

        // 1. Borrar portada física si existe
        if ($coleccion->imagen_url) {
            $rutaArchivo = public_path('storage/' . $coleccion->imagen_url);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
        }

        // NOTA: No borramos las imágenes de la galería (tabla Imagen) físicamente aquí,
        // porque esas imágenes podrían estar usándose en un producto.
        // Solo se rompe el vínculo en la base de datos automáticamente.

        $coleccion->delete();

        return back()->with('success', 'Colección eliminada correctamente.');
    }

    public function coleccionEdit($id)
    {
        $coleccion = Coleccion::findOrFail($id);
        return view('admin.colecciones.edit', compact('coleccion'));
    }

    public function coleccionUpdate(Request $request, $id)
    {
        $coleccion = Coleccion::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'imagen_coleccion' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // 1. Actualizar datos de texto
        $coleccion->Nombre = $request->nombre;
        $coleccion->Año = $request->anio;

        // 2. Gestión de la Imagen (Solo si se ha subido una nueva)
        if ($request->hasFile('imagen_coleccion')) {

            // A. Borrar la imagen antigua si existe
            if ($coleccion->imagen_url) {
                $rutaAntigua = public_path('storage/' . $coleccion->imagen_url);
                if (file_exists($rutaAntigua)) {
                    unlink($rutaAntigua);
                }
            }

            // B. Subir la nueva
            $archivo = $request->file('imagen_coleccion');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '', $archivo->getClientOriginalName());
            $nombreArchivo = time() . '_' . $nombreLimpio;

            $archivo->move(public_path('storage/colecciones'), $nombreArchivo);

            // C. Actualizar ruta en BD
            $coleccion->imagen_url = 'colecciones/' . $nombreArchivo;
        }

        $coleccion->save();

        return redirect()->route('admin.colecciones.index')->with('success', 'Colección actualizada correctamente.');
    }

    public function productoEdit($id)
    {
        // 1. Cargar producto con TODAS sus relaciones para poder rellenar el formulario
        $producto = Producto::with(['imagenes', 'tallas', 'categorias', 'colecciones'])->findOrFail($id);

        // 2. Cargar listas para los selectores
        $categorias = Categoria::all();
        $colecciones = Coleccion::all();

        return view('admin.productos.edit', compact('producto', 'categorias', 'colecciones'));
    }

    public function productoUpdate(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        // 1. Validar (Imagen es nullable porque si no subes nada, se queda la vieja)
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'stock_s' => 'nullable|integer|min:0',
            'stock_m' => 'nullable|integer|min:0',
            'stock_l' => 'nullable|integer|min:0',
            'stock_unica' => 'nullable|integer|min:0',
            'categorias' => 'nullable|array',
            'colecciones' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // 2. Actualizar datos básicos
            $producto->update([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Precio' => $request->precio,
            ]);

            // 3. Actualizar Relaciones (Categorías y Colecciones)
            // sync() borra las antiguas y pone las nuevas automáticamente
            $producto->categorias()->sync($request->categorias ?? []);
            $producto->colecciones()->sync($request->colecciones ?? []);

            // 4. Actualizar Stock
            // Usamos updateOrCreate para cada talla
            $tallas = [
                'S' => $request->stock_s,
                'M' => $request->stock_m,
                'L' => $request->stock_l,
                'unica' => $request->stock_unica,
            ];

            foreach ($tallas as $nombreTalla => $cantidad) {
                // Si la cantidad es nula, asumimos 0
                $cantidad = $cantidad ?? 0;

                \App\Models\ProductoStock::updateOrCreate(
                    ['id_producto' => $producto->id_producto, 'talla' => $nombreTalla],
                    ['stock' => $cantidad]
                );
            }

            // 5. Gestión de Imagen (Solo si suben una nueva)
            if ($request->hasFile('imagen')) {
                // A. Borrar imágenes viejas (Físico y BD)
                foreach ($producto->imagenes as $imgAntigua) {
                    $rutaFisica = public_path('storage/' . $imgAntigua->URL);
                    if (file_exists($rutaFisica)) unlink($rutaFisica);
                    $imgAntigua->delete();
                }

                // B. Subir nueva
                $archivo = $request->file('imagen');
                $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '', $archivo->getClientOriginalName());
                $nombreArchivo = time() . '_' . $nombreLimpio;
                $archivo->move(public_path('storage/productos'), $nombreArchivo);

                // C. Crear registro
                Imagen::create([
                    'URL' => 'productos/' . $nombreArchivo,
                    'producto_id' => $producto->id_producto,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
