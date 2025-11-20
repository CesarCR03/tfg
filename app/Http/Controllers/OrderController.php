<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cesta;
use App\Models\Producto;
use App\Models\ProductoStock;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\DetallePedido;
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

        try {
            DB::beginTransaction();

            // 1. Calcular total
            $totalPedido = $cesta->productos->sum(function($item) {
                return $item->Precio * $item->pivot->cantidad;
            });

            // 2. Crear el Pedido (Cabecera)
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'total' => $totalPedido,
                'estado' => 'pagado'
            ]);

            // 3. Bucle ÚNICO: Gestionar Stock y Crear Detalles a la vez
            foreach ($cesta->productos as $item) {

                // --- A) GESTIÓN DE STOCK ---

                // Buscar la variante específica (talla) y bloquearla para evitar condiciones de carrera
                $varianteStock = ProductoStock::where('id_producto', $item->id_producto)
                    ->where('talla', $item->pivot->talla)
                    ->lockForUpdate()
                    ->first();

                // Comprobar si existe y si hay stock suficiente
                if (!$varianteStock || $varianteStock->stock < $item->pivot->cantidad) {
                    throw new \Exception('No hay suficiente stock para: ' . $item->Nombre . ' (Talla: ' . $item->pivot->talla . ')');
                }

                // Descontar stock
                $varianteStock->stock -= $item->pivot->cantidad;
                $varianteStock->save();


                // --- B) GUARDAR DETALLE DEL PEDIDO ---

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->id_producto,
                    'nombre_producto' => $item->Nombre, // Guardamos el nombre por si cambia en el futuro
                    'precio_unitario' => $item->Precio,
                    'cantidad' => $item->pivot->cantidad,
                    'talla' => $item->pivot->talla,
                ]);
            }

            // 4. Vaciar el carrito
            $cesta->productos()->detach();

            // 5. Confirmar cambios
            DB::commit();

            return redirect()->route('home')->with('success', '¡Pedido realizado con éxito!');

        } catch (\Exception $e) {
            // Si algo falla (ej. falta de stock), deshacer todo
            DB::rollBack();

            return redirect()->route('cart.show')->with('error', $e->getMessage());
        }
    }
}
