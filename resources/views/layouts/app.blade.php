<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Tienda')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')

<script src="{{ asset('js/TrabajoDIW.js') }}"></script>
@stack('scripts')
</body>
</html>
