{{-- resources/views/partials/footer.blade.php --}}
<footer>
    <div class="footer-content">
        <nav>
            <span class="enlacesI">
                <a href="https://www.instagram.com/golfwang" target="_blank">
                    <img src="{{ asset('Img/PaginaPrincipal/instagram_6422200.png') }}" alt="Instagram">
                </a>
                <a href="https://x.com/tylerthecreator" target="_blank">
                    <img src="{{ asset('Img/PaginaPrincipal/twitter-alt_12107622.png') }}" alt="X">
                </a>
            </span>
            <div class="footer-center">
                <img src="{{ asset('Img/PaginaPrincipal/on_black_logo_transparent.png') }}"
                     id="logo" alt="logo tienda">
            </div>
            <span class="enlacesD">
                <a href="{{ route('terminos') }}">Términos y condiciones</a>
                <a href="{{ route('politica.cookies') }}">Política de Cookies</a>
            </span>
        </nav>
    </div>
</footer>


