{{-- resources/views/productos.blade.php --}}
@extends('layouts.app')

@section('title', $title ?? 'Tienda') {{-- Usamos la variable $title si existe --}}

@section('content')
    <main>
        {{-- 1. NAVEGADOR DE CATEGORÍAS --}}
        <div class="capaNavegador">
            <ul class="navegador">
                @php
                    if (isset($currentCollectionId)) {
                        $todoRoute = route('coleccion', $currentCollectionId);
                        $title = 'Colección ' . $currentCollectionId; // O busca el nombre si lo pasas
                    } else {
                        $todoRoute = route('tienda');
                        $title = 'Tienda';
                    }
                    // Esto es opcional si ya usas View::share en otro lado
                    // View::share('title', $title);
                @endphp

                <li>·</li>
                <li><a href="{{ $todoRoute }}">Todo</a></li>
                <li>·</li>

                @foreach ($categories as $category)
                    <li>
                        @if (isset($currentCollectionId))
                            <a href="{{ route('coleccion.categoria.show', [
                                'idColeccion' => $currentCollectionId,
                                'idCategoria' => $category->id_categoria
                            ]) }}">
                                {{ $category->Nombre }}
                            </a>
                        @else
                            <a href="{{ route('categoria.show', $category->id_categoria) }}">
                                {{ $category->Nombre }}
                            </a>
                        @endif
                    </li>
                    <li>·</li>
                @endforeach
            </ul>
        </div>

        {{-- 2. REJILLA DE PRODUCTOS --}}
        <div class="capaTabla">
            <div class="coleccionesTablet">
                @foreach ($productos as $producto)
                    <div class="item">
                        <a href="{{ route('productos.show', $producto->id_producto) }}">
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
        <div class="pagination-area">
            {{-- Esto genera los números de página automáticamente --}}
            {{ $productos->links() }}
        </div>
    </main>
@endsection
