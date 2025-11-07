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
                    <div class="profile-dropdown">

                    {{-- Este es el icono que se ve siempre --}}
                    <img src="{{ asset('Img/PaginaPrincipal/user_17740782.png') }}" alt="Mi cuenta" class="profile-icon">

                    {{-- Este es el menú que aparece al pasar el ratón --}}
                    <div class="dropdown-content">

                        {{-- 1. Enlace a Mi Perfil --}}
                        <a href="{{ route('profile.edit') }}">Mi Perfil</a>

                        {{-- 2. Enlace a Admin (si es admin) --}}
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" style="color: #e74c3c;">Panel Admin</a>
                        @endif
                        {{-- 3. Formulario de Cerrar Sesión --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                               Cerrar Sesión
                            </a>
                        </form>
                    </div>
                    </div>
                @endauth
                <a href="{{ route('home') }}">
                    <img src="{{ asset('Img/PaginaPrincipal/world_16396755.png') }}" alt="cambiar idioma">
                </a>
            </span>
            <span class="enlacesP">
                <a href="{{route('home')}}"><img src="{{ asset('Img/PaginaPrincipal/on_black_logo_transparent.png') }}"></a>
                <a href="{{ route('tienda') }}" class="aux">Tienda</a>
                <a href="{{ url('menu/drops') }}" class="aux">Drops</a>
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
