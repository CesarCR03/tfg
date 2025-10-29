@extends('layouts.app')

@section('title', 'Retail - Tiendas Físicas')

@section('content')
    <main>
        <div class="mapa">
            {{-- NOTA IMPORTANTE: La URL del iframe que usabas (https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1349.2295822036936!2d-0.9320821632147883!3d41.63641300151047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1ses!2ses!4v1731917312784!5m2!1ses!2ses)
                 es un marcador de posición y no mostrará un mapa real.
                 Necesitarás una URL de Google Maps válida o una clave de API.
                 Por ahora, mantenemos la estructura. --}}
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1349.2295822036936!2d-0.9320821632147883!3d41.63641300151047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1ses!2ses!4v1731917312784!5m2!1ses!2ses"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        <div class="infoTienda">
            <p>De lunes a viernes de 9:00 a 13:00</p>
            <p>Contacto: 976 00 00 01</p>
        </div>
    </main>
@endsection
