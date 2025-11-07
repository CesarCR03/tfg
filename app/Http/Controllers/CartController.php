<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Producto;
use App\Models\ProductoStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Función auxiliar para obtener o crear la Cesta basada en el usuario o la sesión.
     */
    protected function getOrCreateCart()
    {
        // 1. Usuario autenticado
        if (Auth::check()) {
            return Cesta::firstOrCreate(['user_id' => Auth::id()]);
        }

        // 2. Usuario invitado (usando ID de sesión)
        $sessionId = session()->getId();
        return Cesta::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }


    /**
     * Muestra la página principal del carrito.
     */
    public function showCart()
    {
        $cesta = $this->getOrCreateCart();

        // Carga la cesta con los productos y sus datos pivote
        $cesta->load('productos');

        // Necesitas crear esta vista: resources/views/cart/index.blade.php
        return view('cart.index', compact('cesta'));
    }

    /**
     * Agrega un producto a la cesta o actualiza la cantidad.
     */
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

        // --- INICIO DE LA NUEVA LÓGICA DE STOCK ---
        // 1. Encontrar la variante de stock específica
        $varianteStock = ProductoStock::where('id_producto', $productoId)
            ->where('talla', $talla)
            ->first();

        // 2. Comprobar si la variante existe
        if (!$varianteStock) {
            // 'back()' redirige al usuario a la página del producto
            return redirect()->back()->with('error', 'Producto o talla no encontrado.');
        }

        // 3. Comprobar si hay stock suficiente para la cantidad solicitada
        if ($varianteStock->stock < $cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock para esa talla. Solo quedan: ' . $varianteStock->stock);
        }

        $cesta = $this->getOrCreateCart();

        // 4. Comprobar si el producto (con esa talla) ya está en el carrito
        $existingItem = $cesta->productos()
            ->wherePivot('id_producto', $productoId)
            ->wherePivot('talla', $talla)
            ->first();

        if ($existingItem) {
            // 5. Si ya existe, comprobar que la nueva cantidad total no supere el stock
            $newQuantity = $existingItem->pivot->cantidad + $cantidad;

            if ($varianteStock->stock < $newQuantity) {
                // 'route('cart.show')' redirige al carrito
                return redirect()->route('cart.show')->with('error', 'No puedes añadir más. El stock máximo para esta talla (' . $talla . ') es: ' . $varianteStock->stock);
            }

            // Si hay stock, actualiza la cantidad
            $cesta->productos()->updateExistingPivot($productoId, [
                'cantidad' => $newQuantity
            ], false);

        } else {
            // Si no existe, lo adjunta (ya comprobamos el stock al inicio)
            $cesta->productos()->attach($productoId, [
                'cantidad' => $cantidad,
                'talla' => $talla
            ]);
        }
        // --- FIN DE LA NUEVA LÓGICA DE STOCK ---

        return redirect()->route('cart.show')->with('success', 'Producto agregado al carrito!');
    }

    // Placeholder para futuras funciones
    public function updateCart(Request $request)
    {
        // Validar los campos esenciales
        $request->validate([
            'id_producto' => 'required|exists:Producto,id_producto',
            'talla' => 'required|string|max:10',
            'cantidad' => 'required|integer|min:0', // Permite 0 para eliminar
        ]);

        $cesta = $this->getOrCreateCart();
        $productoId = $request->id_producto;
        $talla = $request->talla;
        $cantidad = $request->cantidad;

        if ($cantidad == 0) {
            // Si la cantidad es 0, simplemente lo eliminamos
            return $this->removeFromCart($productoId, $talla);
        }

        // --- INICIO DE LA NUEVA LÓGICA DE STOCK ---
        // 1. Encontrar la variante de stock
        $varianteStock = ProductoStock::where('id_producto', $productoId)
            ->where('talla', $talla)
            ->first();

        // 2. Comprobar si la cantidad deseada supera el stock disponible
        if (!$varianteStock || $varianteStock->stock < $cantidad) {
            $stockMaximo = $varianteStock ? $varianteStock->stock : 0;
            return redirect()->route('cart.show')->with('error', 'No hay suficiente stock. Máximo disponible para la talla ' . $talla . ': ' . $stockMaximo);
        }

        // 3. Si hay stock, actualizamos
        $cesta->productos()->updateExistingPivot($productoId, [
            'cantidad' => $cantidad
        ], false);

        $message = 'Cantidad actualizada.';
        // --- FIN DE LA NUEVA LÓGICA DE STOCK ---

        return redirect()->route('cart.show')->with('success', $message);
    }

    public function removeFromCart($idProducto, $talla)
    {
        $cesta = $this->getOrCreateCart();

        // Elimina el registro de la tabla pivote que coincida con el producto y la talla
        $cesta->productos()->wherePivot('id_producto', $idProducto)
            ->wherePivot('talla', $talla)
            ->detach($idProducto); // El detach borra la relación

        return redirect()->route('cart.show')->with('success', 'Producto eliminado de la cesta.');
    }
}
