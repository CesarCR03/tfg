<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cesta;
use App\Models\Producto;
use App\Models\ProductoStock;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Función auxiliar (copiada de CartController) para obtener la Cesta.
     */
    protected function getActiveCart()
    {
        if (Auth::check()) {
            return Cesta::firstOrCreate(['user_id' => Auth::id()]);
        }
        $sessionId = session()->getId();
        return Cesta::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    /**
     * Procesa el pedido, descuenta el stock y limpia el carrito.
     */
    public function processOrder(Request $request)
    {
        $cesta = $this->getActiveCart();
        $cesta->load('productos');

        if ($cesta->productos->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Tu cesta está vacía.');
        }

        // Usamos una 'Transacción' de la Base de Datos.
        // Esto asegura que si algo falla (ej. no hay stock de un producto),
        // no se descuenta el stock de los otros. O todo o nada.
        try {
            DB::beginTransaction();

            // 1. RECORREMOS LOS PRODUCTOS DE LA CESTA
            foreach ($cesta->productos as $item) {

                // 2. BUSCAMOS LA VARIANTE (TALLA/STOCK) REAL EN LA BD
                $varianteStock = ProductoStock::where('id_producto', $item->id_producto)
                    ->where('talla', $item->pivot->talla)
                    ->lockForUpdate() // Bloquea esta TALLA específica
                    ->first();

                // 3. COMPROBAMOS SI HAY SUFICIENTE STOCK PARA ESA TALLA
                if (!$varianteStock || $varianteStock->stock < $item->pivot->cantidad) {
                    // Si no existe la variante o no hay stock, cancelamos
                    throw new \Exception('No hay suficiente stock para: ' . $item->Nombre . ' (Talla: ' . $item->pivot->talla . ')');
                }

                // 4. DESCONTAMOS EL STOCK
                $varianteStock->stock -= $item->pivot->cantidad; // Restamos la cantidad
                $varianteStock->save(); // Guardamos la variante con el nuevo stock
            }

            // 5. (AQUÍ IRÍA LA LÓGICA DE PAGO: Stripe, PayPal, etc.)
            // ...

            // 6. (AQUÍ IRÍA LA CREACIÓN DEL PEDIDO EN LA BD)
            // Ej: Order::create([...]);

            // 7. VACIAR EL CARRITO
            // 'detach' borra todas las relaciones (productos) de la cesta.
            $cesta->productos()->detach();

            // 8. SI TODO HA IDO BIEN, GUARDAMOS LOS CAMBIOS
            DB::commit();

            // Redirigimos al inicio con un mensaje de éxito
            return redirect()->route('home')->with('success', '¡Pedido realizado con éxito!');

        } catch (\Exception $e) {
            // 9. SI ALGO HA FALLADO, REVERTIMOS TODO
            DB::rollBack();

            // Devolvemos al usuario al carrito con el mensaje de error
            return redirect()->route('cart.show')->with('error', $e->getMessage());
        }
    }
}
