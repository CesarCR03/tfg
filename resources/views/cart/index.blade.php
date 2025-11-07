@extends('layouts.app')

@section('title', 'Tu Cesta de Compra')

@section('content')
    <main>
        <div class="cart-container">
            <h1 class="cart-header-title">CESTA</h1>

            @if ($cesta->productos->isEmpty())
                <div class="empty-cart-message min-height-viewport">
                    <p>La cesta está vacía, espabila un poco.</p>
                    <a href="{{ route('tienda') }}">Comprar</a>
                </div>
            @else
                @php
                    $totalGeneral = 0;
                @endphp

                {{-- Encabezados de la Lista --}}
                <div class="cart-column-headers">
                    <span class="header-producto">PRODUCTO</span>
                    <span class="header-cantidad">CANTIDAD</span>
                    <span class="header-total">TOTAL</span>
                </div>

                {{-- Contenedor de Artículos --}}
                <div class="cart-items-list">
                    @foreach ($cesta->productos as $item)
                        @php
                            $subtotal = $item->Precio * $item->pivot->cantidad;
                            $totalGeneral += $subtotal;
                        @endphp

                        {{-- INICIO DE LA TARJETA DE PRODUCTO --}}
                        <div class="cart-item-card" data-product-id="{{ $item->id_producto }}" data-talla="{{ $item->pivot->talla }}">

                            {{-- Columna 1: IMAGEN Y DETALLES --}}
                            <div class="cart-item-details-block">
                                <img src="{{ asset('storage/' . optional($item->imagenes->first())->URL) }}"
                                     alt="{{ $item->Nombre }}"
                                     class="cart-item-image">

                                <div class="cart-item-text-info">
                                    <a href="{{ route('productos.show', $item->id_producto) }}" class="item-name-link">
                                        {{ $item->Nombre }}
                                    </a>
                                    <p class="item-price-unit">€{{ number_format($item->Precio, 2) }}</p>
                                    <p class="item-talla-info">{{ $item->pivot->talla }}</p>
                                </div>
                            </div>

                            {{-- Columna 2: CANTIDAD y Botón Quitar --}}
                            <div class="cart-item-actions">

                                <form action="{{ route('cart.update') }}" method="POST" class="quantity-form-js">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id_producto" value="{{ $item->id_producto }}">
                                    <input type="hidden" name="talla" value="{{ $item->pivot->talla }}">
                                    <input type="hidden" name="cantidad" value="{{ $item->pivot->cantidad }}" class="qty-hidden-input">

                                    <div class="quantity-controls">
                                        <button type="button" class="qty-btn qty-minus-js" data-action="minus" @if($item->pivot->cantidad <= 1) disabled @endif >−</button>
                                        <span class="qty-display">{{ $item->pivot->cantidad }}</span>
                                        <button type="button" class="qty-btn qty-plus-js" data-action="plus">+</button>
                                    </div>
                                </form>

                                {{-- Botón Quitar/Eliminar --}}
                                <form action="{{ route('cart.remove', ['idProducto' => $item->id_producto, 'talla' => $item->pivot->talla ]) }}" method="POST" class="remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-text-btn">Quitar</button>
                                </form>
                            </div>

                            {{-- Columna 3: TOTAL POR ARTÍCULO --}}
                            <span class="cart-item-line-total">€{{ number_format($subtotal, 2) }}</span>
                        </div>
                        {{-- FIN DE LA TARJETA DE PRODUCTO --}}
                    @endforeach
                </div>

                {{-- Resumen y Botón de Pago --}}
                <form action="{{ route('order.process') }}" method="POST">
                    @csrf
                    <div class="cart-summary-footer">
                        <div class="summary-details">
                            <p class="summary-total-text">Total: <span class="summary-total-value">€{{ number_format($totalGeneral, 2) }} EUR</span></p>
                        </div>

                        {{-- El botón ahora es de tipo 'submit' --}}
                        <button type="submit" class="checkout-btn">FINALIZAR COMPRA</button>
                    </div>
                </form>
            @endif
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cartContainer = document.querySelector('.cart-container');

                if (cartContainer) {
                    cartContainer.addEventListener('click', function(e) {
                        const target = e.target;

                        // 1. Identificar si se hizo clic en un botón de cantidad
                        if (target.classList.contains('qty-minus-js') || target.classList.contains('qty-plus-js')) {
                            e.preventDefault();

                            const form = target.closest('.quantity-form-js');
                            const hiddenInput = form.querySelector('.qty-hidden-input');
                            let currentQuantity = parseInt(hiddenInput.value);

                            // Lógica de incremento y decremento
                            if (target.classList.contains('qty-plus-js')) {
                                currentQuantity += 1;
                            } else if (target.classList.contains('qty-minus-js')) {
                                // Evita que baje de 0. El controlador se encarga de la eliminación si llega a 0.
                                currentQuantity = Math.max(0, currentQuantity - 1);
                            }

                            // 2. Actualizar el valor y enviar el formulario
                            hiddenInput.value = currentQuantity;
                            form.submit();
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
