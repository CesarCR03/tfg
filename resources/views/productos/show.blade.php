@extends('layouts.app')

@section('title', $producto->Nombre)

@section('content')
    <main>
        {{-- Flecha de retroceso - Usamos la ruta 'tienda' o 'coleccion' si es necesario --}}
        <div class="flechaAtras">
            <a href="{{ url()->previous() }}"> {{-- Usar url()->previous() es más flexible para volver al listado --}}
                <img src="{{ asset('Img/PaginaPrincipal/double-arrows_10420972.png') }}" alt="Atrás">
            </a>
        </div>

        <div class="capaTabla">
            <table class="prendas">
                <tr>
                    {{-- COLUMNA 1: IMAGEN DEL PRODUCTO (Galería) --}}
                    <td colspan="1">
                        {{-- La galería de imágenes de tu código original, adaptada a la tabla --}}
                        <div class="galeria-imagenes">
                            @foreach($producto->imagenes as $imagen)
                                {{-- Si quieres solo la primera imagen para el detalle: --}}
                                @if ($loop->first)
                                    <img src="{{ asset('storage/' . $imagen->URL) }}"
                                         alt="Imagen principal de {{ $producto->Nombre }}">
                                @endif
                                {{-- Si quieres mostrar todas las imágenes: --}}
                                {{-- <img src="{{ asset('storage/' . $imagen->URL) }}" alt="Imagen de {{ $producto->Nombre }}"> --}}
                            @endforeach
                        </div>
                    </td>

                    {{-- COLUMNA 2: NOMBRE, DESCRIPCIÓN Y ACCIONES --}}
                    <td class="dimensionesTexto" style="vertical-align: top;">
                        {{-- Atributo: Nombre --}}
                        <h1>{{ $producto->Nombre }}</h1>

                        {{-- Atributo: Descripción --}}
                        <p>{{ $producto->Descripcion }}</p>

                        <br>

                        {{-- Fila para precio, talla y botón de compra --}}
                        <table>
                            <tr>
                                {{-- Precio --}}
                                <td><span class="precio">{{ number_format($producto->Precio, 2) }} €</span></td>

                                {{-- Select Talla --}}
                                <td>
                                    <select name="talla" id="talla">
                                        <option value="1">S</option>
                                        <option value="2">M</option>
                                        <option value="3">L</option>
                                    </select>
                                </td>

                                {{-- Botón de Carrito (No funcional) --}}
                                <td>
                                    <input type="button" id="botonCompra" value="Añadir al carrito">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </main>
@endsection
