{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<main>
    <div class="colecciones">
        <div class="coleccion1">
            <a href="{{route('coleccion',1)}}">
                <img src="{{ asset('Img/PaginaPrincipal/Coleccion1.png') }}" alt="Winter2021">
            </a>
        </div>
        <br>
        <div class="coleccion2">
            <a href="{{route('coleccion',2)}}">
                <img src="{{ asset('Img/PaginaPrincipal/Coleccion2.png') }}" alt="Winter2020">
            </a>
        </div>
    </div>
</main>
@endsection
