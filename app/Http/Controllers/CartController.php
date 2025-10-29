<?php

namespace App\Http\Controllers;

use App\Models\Cesta;
use App\Models\Producto; // Necesario para referencias
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

        $cesta = $this->getOrCreateCart();
        $productoId = $request->id_producto;
        $cantidad = $request->cantidad;
        $talla = $request->talla;

        // El 'wherePivot' es la clave para manejar un carrito con diferentes tallas
        $existingItem = $cesta->productos()
            ->wherePivot('id_producto', $productoId)
            ->wherePivot('talla', $talla)
            ->first();

        if ($existingItem) {
            // Si el producto Y la talla ya existen, actualiza la cantidad
            $newQuantity = $existingItem->pivot->cantidad + $cantidad;
            $cesta->productos()->updateExistingPivot($productoId, [
                'cantidad' => $newQuantity
            ], false); // El 'false' es para indicar que las claves pivote son las por defecto
        } else {
            // Si no existe, lo adjunta a la cesta
            $cesta->productos()->attach($productoId, [
                'cantidad' => $cantidad,
                'talla' => $talla
            ]);
        }

        // Redirige al usuario al carrito (necesitas definir esta ruta)
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

        if ($cantidad > 0) {
            // Actualizar la cantidad del producto y talla específicos
            $cesta->productos()->updateExistingPivot($productoId, [
                'cantidad' => $cantidad
            ], false);

            $message = 'Cantidad actualizada.';
        } else {
            // Si la cantidad es 0, lo eliminamos (como medida de seguridad/usabilidad)
            $cesta->productos()->wherePivot('id_producto', $productoId)
                ->wherePivot('talla', $talla)
                ->detach($productoId);

            $message = 'Producto eliminado de la cesta.';
        }

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
