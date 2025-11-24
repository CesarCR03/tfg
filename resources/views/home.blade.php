@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    <main>
        <div class="colecciones">
            @foreach($colecciones as $coleccion)
                <div class="coleccion-item" style="margin-bottom: 20px;">
                    {{-- Enlace a la colección usando su ID dinámico --}}
                    <a href="{{ route('coleccion', $coleccion->id_coleccion) }}">

                        {{-- LOGICA DE IMAGEN: --}}
                        @if($coleccion->imagen_url)
                            {{-- 1. Si tiene imagen en Base de Datos --}}
                            {{-- Asumiendo que guardaste la ruta como 'colecciones/nombre.jpg' --}}
                            <img src="{{ asset('storage/' . $coleccion->imagen_url) }}"
                                 alt="{{ $coleccion->Nombre }}"
                                 style="max-width: 100%; height: auto;">
                        @else
                            {{-- 2. Fallback para colecciones antiguas (IDs 1 y 2) sin imagen en BD --}}
                            {{-- Intenta cargar la imagen estática basada en el ID --}}
                            <img src="{{ asset('Img/PaginaPrincipal/Coleccion' . $coleccion->id_coleccion . '.png') }}"
                                 alt="{{ $coleccion->Nombre }}"
                                 style="max-width: 100%; height: auto;">
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </main>
@endsection
