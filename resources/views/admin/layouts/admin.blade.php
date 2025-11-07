<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

</head>
<body>

<div class="admin-sidebar">
    <h2>Admin TFG</h2>
    <nav>
        {{--
            Comprobamos si la ruta activa es 'admin.dashboard'
            para ponerle la clase 'active' que definimos en el CSS
        --}}
        <a href="{{ route('admin.dashboard') }}"
           class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        {{-- <a href="#">Gestionar Productos</a> --}}
        {{-- <a href="#">Ver Usuarios</a> --}}

        <hr style="opacity: 0.2; border: 0; border-top: 1px solid #567; margin: 20px 0;">

        <a href="{{ route('home') }}" target="_blank">Ver Tienda</a>
    </nav>
</div>

<main class="admin-content">
    <h1>@yield('title')</h1>

    @yield('content')
</main>

</body>
</html>
