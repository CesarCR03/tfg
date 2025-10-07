{{-- resources/views/productos.blade.php --}}
@extends('layouts.app')

@section('title', 'Coleccion1')

@section('content')
    <main>
        <div class="capaNavegador">
            <ul class="navegador">
                <li>·</li>
                <li><a href="Coleccion1.html">Todo</a></li>
                <li>·</li>
                <li><a href="Superiores2021.html">Superiores</a></li>
                <li>·</li>
                <li><a href="Pantalones2021.html">Pantalones</a></li>
                <li>·</li>
                <li><a href="Accesorios2021.html">Accesorios</a></li>
                <li>·</li>
            </ul>
        </div>
        <div class="capaTabla">
            <div class="coleccionesTablet">
                @foreach ($productos as $producto)
                    <div class="item">
                        <a>
                            @if ($producto->imagenes->isNotEmpty())
                                <img src="{{ asset('storage/' . $producto->imagenes->first()->URL) }}"
                                     data-hover="{{ asset('storage/' . $producto->imagenes->first()->URL) }}"
                                     class="imagenesHover">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" class="imagenesHover">
                            @endif
                        </a>
                        <p>{{ $producto->Nombre }}</p>
                        <p>{{ number_format($producto->Precio, 2) }}€</p>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
