{{-- resources/views/partials/header.blade.php --}}
<header>
    <div class="banner-container">
        <div class="banner-content">
            <span class="carrusel">Nuevo drop el viernes a las 12 am.</span>
            <span class="carrusel">@0.000.000</span>
        </div>
    </div>
    <div class="header-content">
        <nav>
            <span class="enlacesI">
                <span class="hamburg">☰</span>
                @guest
                    <a href="{{ route('login') }}" title="Iniciar Sesión">
                        <img src="{{ asset('Img/PaginaPrincipal/user_17740782.png') }}" alt="Iniciar Sesión">
                    </a>
                @endguest

                @auth
                    {{-- USUARIO: Clicar lleva directamente al Perfil --}}
                    <a href="{{ route('profile.edit') }}" title="Mi Perfil">
            <img src="{{ asset('Img/PaginaPrincipal/user_17740782.png') }}" alt="Mi Perfil">
        </a>
                @endauth
            </span>
            <span class="enlacesP">
                <a href="{{ route('tienda') }}" class="aux">Tienda</a>
                <a href="{{ url('menu/drops') }}" class="aux">Drops</a>
                <a href="{{ route('home') }}">
                <img src="{{ asset('Img/PaginaPrincipal/on_black_logo_transparent.png') }}" alt="Logo" class="header-logo"></a>
                <a href="{{ route('locations') }}" class="aux">Locations</a>
                <a class="aux">Influencers</a>
            </span>
            <ul class="menuPrincipal">
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li><a href="{{ route('tienda') }}">Tienda</a></li>
                <li><a href="{{ url('menu/drops') }}">Drops</a></li>
                <li><a href="{{ route('locations') }}" class="aux">Locations</a></li>
                <li><a class="aux">Influencers</a></li>
            </ul>
            <span class="enlacesD">
                <img src="{{ asset('Img/PaginaPrincipal/share-alt-square_10470668.png') }}" alt="compartir">
                <a href="{{ route('cart.show') }}">
                    <img src="{{ asset('Img/PaginaPrincipal/cart-arrow-down_9798256.png') }}" alt="cesta de compra">
                </a>
            </span>
        </nav>
    </div>
</header>
