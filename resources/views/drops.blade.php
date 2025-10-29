@extends('layouts.app')

@section('title', 'Drops - ' . ($selectedCollection->Nombre ?? 'Escaparate'))

@section('content')
    <main>
        <div class="drops-gallery-container">

            {{-- COLUMNA 1: MENÚ DE COLECCIONES --}}
            <div class="drops-menu-area">
                <div class="fechasDrops">
                    <h2 class="menu-title">LOOKBOOK</h2>
                    <ul>
                        @foreach ($allCollections as $collection)
                            <li>
                                <a href="{{ route('drops.show', $collection->id_coleccion) }}"
                                   class="{{ $collection->id_coleccion == $currentCollectionId ? 'active-drop-link' : '' }}">
                                    {{ $collection->Nombre }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- COLUMNA 2 Y 3: GALERÍA Y MINIATURAS --}}
            @if ($selectedCollection && $productos->isNotEmpty())
                <div class="gallery-content-area">

                    {{-- IMAGEN PRINCIPAL --}}
                    <div class="main-image-view">
                        {{-- La imagen se carga con la URL del primer producto por defecto --}}
                        <img id="main-drop-image"
                             src="{{ asset('storage/' . optional($productos->first()->imagenes->first())->URL) }}"
                             alt="{{ $productos->first()->Nombre }}"
                             class="drop-main-img">
                    </div>

                    {{-- MINIATURAS (THUMBNAILS) --}}
                    <div class="thumbnails-grid-area">
                        <div class="thumbnails-grid">
                            @foreach ($productos as $producto)
                                <div class="thumbnail-item">
                                    <img src="{{ asset('storage/' . optional($producto->imagenes->first())->URL) }}"
                                         alt="{{ $producto->Nombre }}"
                                         data-full-src="{{ asset('storage/' . optional($producto->imagenes->first())->URL) }}"
                                         class="thumbnail-img">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="gallery-content-area">
                    <p>Selecciona una colección o no hay productos en la colección actual.</p>
                </div>
            @endif
        </div>
    </main>

    {{-- SCRIPTS PARA INTERACTIVIDAD --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mainImage = document.getElementById('main-drop-image');
                const thumbnails = document.querySelectorAll('.thumbnail-img');

                thumbnails.forEach(thumbnail => {
                    thumbnail.addEventListener('click', function() {
                        const fullSrc = this.getAttribute('data-full-src');

                        // 1. Cambia la fuente de la imagen principal al hacer clic
                        if (mainImage && fullSrc) {
                            mainImage.src = fullSrc;
                            mainImage.alt = this.alt;
                        }

                        // 2. Opcional: Resaltar la miniatura activa (clase CSS)
                        thumbnails.forEach(t => t.classList.remove('active-thumbnail'));
                        this.classList.add('active-thumbnail');
                    });

                    // 3. Establecer la primera miniatura como activa al cargar
                    if (mainImage && thumbnail === thumbnails[0]) {
                        thumbnail.classList.add('active-thumbnail');
                    }
                });
            });
        </script>
    @endpush
@endsection
