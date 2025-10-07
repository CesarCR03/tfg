@extends('layouts.app')

@section('title', $producto->Nombre)

@section('content')
    <div class="detalle-producto">
        <h1>{{ $producto->Nombre }}</h1>
        <div class="galeria-imagenes">
            @foreach($producto->imagenes as $imagen)
                <img src="{{ Storage::url($imagen->URL) }}"
                     alt="Imagen de {{ $producto->Nombre }}">
            @endforeach
        </div>
        <p>{{ $producto->Descripcion }}</p>
        <span class="precio">{{ number_format($producto->Precio, 2) }} €</span>
    </div>
@endsection

