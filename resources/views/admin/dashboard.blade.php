{{-- Le decimos que use nuestra plantilla de admin --}}
@extends('admin.layouts.admin')

{{-- Definimos el título que aparecerá en el <h1> --}}
@section('title', 'Dashboard')

{{-- Contenido principal de la página --}}
@section('content')
    <p>¡Bienvenido al panel de administrador!</p>
    <p>Desde aquí podrás gestionar los productos, colecciones y usuarios de la tienda.</p>
@endsection
