<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Producto;
use App\Models\ProductoStock;
use App\Models\Cupon; // <--- IMPORTANTE: No olvides importar el modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cesta::firstOrCreate(['user_id' => Auth::id()]);
        }
        $sessionId = session()->getId();
        return Cesta::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    /**
     * Muestra la página principal del carrito con CÁLCULOS DE PRECIO.
     */
    public function showCart()
    {
        $cesta = $this->getOrCreateCart();
        $cesta->load('productos');

        // 1. Calcular Subtotal (Suma de Precio * Cantidad de cada producto)
        $subtotal = $cesta->productos->sum(function($producto) {
            return $producto->Precio * $producto->pivot->cantidad;
        });

        // 2. Calcular Descuento (Si hay cupón en sesión)
        $descuento = 0;
        $cupon = session()->get('cupon'); // Recuperamos datos de la sesión

        if ($cupon) {
            if ($cupon['tipo'] === 'porcentaje') {
                $descuento = $subtotal * ($cupon['valor'] / 100);
            } else {
                // Tipo 'fijo'
                $descuento = $cupon['valor'];
            }
        }

        // 3. Calcular Total Final (Evitando negativos)
        $total = max(0, $subtotal - $descuento);

        // Pasamos todas las variables a la vista
        return view('cart.index', compact('cesta', 'subtotal', 'descuento', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:Producto,id_producto',
            'cantidad' => 'required|integer|min:1',
            'talla' => 'required|string|max:10',
        ]);

        $productoId = $request->id_producto;
        $cantidad = $request->cantidad;
        $talla = $request->talla;

        $varianteStock = ProductoStock::where('id_producto', $productoId)
            ->where('talla', $talla)
            ->first();

        if (!$varianteStock) {
            return redirect()->back()->with('error', 'Producto o talla no encontrado.');
        }

        if ($varianteStock->stock < $cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock. Solo quedan: ' . $varianteStock->stock);
        }

        $cesta = $this->getOrCreateCart();

        $existingItem = $cesta->productos()
            ->wherePivot('id_producto', $productoId)
            ->wherePivot('talla', $talla)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->pivot->cantidad + $cantidad;

            if ($varianteStock->stock < $newQuantity) {
                return redirect()->route('cart.show')->with('error', 'Stock máximo alcanzado (' . $varianteStock->stock . ').');
            }

            $cesta->productos()->updateExistingPivot($productoId, [
                'cantidad' => $newQuantity
            ], false);

        } else {
            $cesta->productos()->attach($productoId, [
                'cantidad' => $cantidad,
                'talla' => $talla
            ]);
        }

        return redirect()->route('cart.show')->with('success', 'Producto agregado al carrito!');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:Producto,id_producto',
            'talla' => 'required|string|max:10',
            'cantidad' => 'required|integer|min:0',
        ]);

        $productoId = $request->id_producto;
        $talla = $request->talla;
        $cantidad = $request->cantidad;

        if ($cantidad == 0) {
            return $this->removeFromCart($productoId, $talla);
        }

        $varianteStock = ProductoStock::where('id_producto', $productoId)
            ->where('talla', $talla)
            ->first();

        if (!$varianteStock || $varianteStock->stock < $cantidad) {
            $stockMaximo = $varianteStock ? $varianteStock->stock : 0;
            return redirect()->route('cart.show')->with('error', 'Stock insuficiente. Máximo: ' . $stockMaximo);
        }

        $cesta = $this->getOrCreateCart();
        $cesta->productos()->updateExistingPivot($productoId, [
            'cantidad' => $cantidad
        ], false);

        return redirect()->route('cart.show')->with('success', 'Cantidad actualizada.');
    }

    public function removeFromCart($idProducto, $talla)
    {
        $cesta = $this->getOrCreateCart();

        $cesta->productos()->wherePivot('id_producto', $idProducto)
            ->wherePivot('talla', $talla)
            ->detach($idProducto);

        return redirect()->route('cart.show')->with('success', 'Producto eliminado.');
    }

    // --- NUEVAS FUNCIONES PARA CUPONES ---

    public function applyCoupon(Request $request)
    {
        $request->validate(['codigo' => 'required|string']);

        $codigo = $request->input('codigo');
        $cupon = Cupon::where('codigo', $codigo)->first();

        // Verificar si existe y si es válido (usando el método del modelo o comprobando fecha manualmente)
        if (!$cupon) {
            return back()->with('error', 'El cupón no existe.');
        }

        // Si tienes el método esValido() en el modelo úsalo, si no, comprueba la fecha aquí:
        if ($cupon->fecha_caducidad && $cupon->fecha_caducidad < now()) {
            return back()->with('error', 'El cupón ha caducado.');
        }

        // Guardar en sesión
        session()->put('cupon', [
            'codigo' => $cupon->codigo,
            'tipo' => $cupon->tipo,
            'valor' => $cupon->valor,
        ]);

        return back()->with('success', 'Cupón ' . $codigo . ' aplicado correctamente.');
    }

    public function removeCoupon()
    {
        session()->forget('cupon');
        return back()->with('success', 'Cupón eliminado.');
    }
}
