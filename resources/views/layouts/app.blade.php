<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Tienda')</title>
    <!-- CSS principal -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Espacio para estilos adicionales -->
    @stack('styles')
    <style>
        body {
            /*background-image: url('{{ asset("Img/PaginaPrincipal/ImagenTraslucida.jpg") }}');*/
            margin: 0;
            padding-top: 140px;
            background-repeat: no-repeat;
            background-position: center ;
            background-size: cover;
        }
    </style>
</head>
<body>
<!-- Header común -->
@include('partials.header')

<!-- Contenido dinámico -->
<main>
    @yield('content')
</main>

<!-- Footer común -->
@include('partials.footer')

<!-- js principal -->
<script src="{{ asset('js/TrabajoDIW.js') }}"></script>
<!-- Espacio para scripts adicionales -->
@stack('scripts')
</body>
</html>

