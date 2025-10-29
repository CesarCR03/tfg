{{-- resources/views/productos.blade.php --}}
@extends('layouts.app')

@section('title', 'Coleccion1')

@section('content')
    <main>
        <div class="capaNavegador">
            <ul class="navegador">
                @php
                    if (isset($currentCollectionId)) {
                        $todoRoute = route('coleccion', $currentCollectionId);
                        // Puedes obtener el nombre real de la colección si lo pasas desde el controlador,
                        // pero por ahora usamos el ID.
                        $title = 'Colección ' . $currentCollectionId;
                    } else {
                        $todoRoute = route('tienda');
                        $title = 'Tienda';
                    }

                    // Actualiza el título de la página (si usas @section('title'))
                    View::share('title', $title);
                @endphp

                <li>·</li>
                <li><a href="{{ $todoRoute }}">Todo</a></li>
                <li>·</li>

                {{-- Generar enlaces de categorías dinámicamente --}}
                {{-- Usamos $categories que viene del controlador --}}
                @foreach ($categories as $category)
                    <li>
                        {{-- Comprueba si estamos en el contexto de una colección (ID NO NULO) --}}
                        @if (isset($currentCollectionId))
                            {{-- Si hay una colección activa, usamos la ruta combinada para filtrar DENTRO de ella --}}
                            <a href="{{ route('coleccion.categoria.show', [
                                'idColeccion' => $currentCollectionId,
                                'idCategoria' => $category->id_categoria // Usamos la columna real de la BD
                            ]) }}">
                                {{ $category->Nombre }}
                            </a>
                        @else
                            {{-- Si no hay colección activa (estamos en /productos), usamos la ruta simple para filtrar en TODAS las colecciones --}}
                            <a href="{{ route('categoria.show', $category->id_categoria) }}">
                                {{ $category->Nombre }}
                            </a>
                        @endif
                    </li>
                    <li>·</li>

            @endforeach
        </div>
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
    </main>
@endsection
