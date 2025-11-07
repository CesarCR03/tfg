@extends('layouts.app')

@section('title', $producto->Nombre)

@section('content')
    <main>
        {{-- CONTENEDOR PRINCIPAL FLEXBOX --}}
        <div class="product-detail-flex-container">

            {{-- Columna 1: IMAGEN --}}
            <div class="product-image-area">
                <div class="galeria-imagenes">
                    @if ($producto->imagenes->isNotEmpty())
                        <img src="{{ asset('storage/' . $producto->imagenes->first()->URL) }}"
                             alt="Imagen principal de {{ $producto->Nombre }}" class="product-main-image">
                    @endif
                </div>
            </div>

            {{-- Columna 2: DETALLES Y FORMULARIO --}}
            <div class="product-info-area">
                <h1 class="product-name-title">{{ $producto->Nombre }}</h1>
                <p class="product-description">{{ $producto->Descripcion }}</p>

                <br>

                {{-- INICIO DEL FORMULARIO DEL CARRITO --}}
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">
                    <input type="hidden" name="cantidad" value="1">
                    {{-- Fila de Controles (Precio, Talla, Cantidad) --}}
                    <div class="product-controls-row">
                        <span class="price-display">{{ number_format($producto->Precio, 2) }} €</span>

                        <select name="talla" id="talla" class="product-talla-select" required>
                            <option value="" disabled selected>Talla</option>

                            {{-- Iteramos sobre las tallas que cargamos desde el controlador --}}
                            @foreach ($producto->tallas as $tallaStock)

                                {{-- Mostramos la talla SOLO SI hay stock --}}
                                @if ($tallaStock->stock > 0)
                                    <option value="{{ $tallaStock->talla }}">
                                        {{ $tallaStock->talla }}
                                    </option>
                                @else
                                    {{-- Opcional: mostrarla como deshabilitada --}}
                                    <option value="{{ $tallaStock->talla }}" disabled>
                                        {{ $tallaStock->talla }} (Agotado)
                                    </option>
                                @endif

                            @endforeach
                        </select>
                    </div>

                    {{-- Botón "Añadir al Carrito" (DEBAJO DE LOS CONTROLES) --}}
                    <div class="add-to-cart-group">
                        <input type="submit" id="botonCompra" class="add-to-cart-btn" value="Añadir al carrito">
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
