@extends('layouts.app')

@section('title', $producto->Nombre)

@section('content')
    <main>
        {{-- Flecha de retroceso --}}
        <div class="flechaAtras">
            <a href="{{ url()->previous() }}">
                <img src="{{ asset('Img/PaginaPrincipal/double-arrows_10420972.png') }}" alt="Atrás">
            </a>
        </div>

        <div class="capaTabla">
            <table class="prendas">
                <tr>
                    {{-- COLUMNA 1: IMAGEN DEL PRODUCTO (Galería) --}}
                    <td colspan="1">
                        <div class="galeria-imagenes">
                            @if ($producto->imagenes->isNotEmpty())
                                <img src="{{ asset('storage/' . $producto->imagenes->first()->URL) }}"
                                     alt="Imagen principal de {{ $producto->Nombre }}">
                            @endif
                        </div>
                    </td>

                    {{-- COLUMNA 2: FORMULARIO DE COMPRA --}}
                    <td class="dimensionesTexto product-detail-info">
                        <h1>{{ $producto->Nombre }}</h1>

                        <p>{{ $producto->Descripcion }}</p>

                        <br>

                        {{-- INICIO DEL FORMULARIO DEL CARRITO (SIN @csrf) --}}
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            @method('POST') {{-- Se mantiene el método HTTP --}}

                            {{-- Campo oculto para enviar el ID del producto --}}
                            <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">

                            <table class="product-form-table">
                                <tr>
                                    {{-- Precio --}}
                                    <td><span class="precio">{{ number_format($producto->Precio, 2) }} €</span></td>

                                    {{-- Select Talla --}}
                                    <td>
                                        <select name="talla" id="talla" required>
                                            <option value="" disabled selected>Talla</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                        </select>
                                    </td>

                                    {{-- Cantidad --}}
                                    <td>
                                        <input type="number" name="cantidad" value="1" min="1" class="quantity-input" required>
                                    </td>

                                    {{-- Botón de Carrito --}}
                                    <td>
                                        <input type="submit" id="botonCompra" value="Añadir al carrito">
                                    </td>
                                </tr>
                            </table>
                        </form>
                        {{-- FIN DEL FORMULARIO --}}
                    </td>
                </tr>
            </table>
        </div>
    </main>
@endsection
