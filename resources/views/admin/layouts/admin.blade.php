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
    <h2>Administrador</h2>
    <nav>
        {{-- Enlace Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        {{-- Enlace Productos --}}
        <a href="{{ route('admin.productos.index') }}"
           class="{{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
            Productos
        </a>

        {{-- NUEVO: Enlace Colecciones --}}
        <a href="{{ route('admin.colecciones.index') }}"
           class="{{ request()->routeIs('admin.colecciones.*') ? 'active' : '' }}">
            Colecciones
        </a>

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
