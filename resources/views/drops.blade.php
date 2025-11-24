@extends('layouts.app')

@section('title', 'Drops - ' . ($selectedCollection->Nombre ?? 'Galería'))

@section('content')
    <main>
        <div class="drops-gallery-container">

            {{-- COLUMNA 1: MENÚ DE COLECCIONES --}}
            <div class="drops-menu-area">
                <div class="fechasDrops">
                    <h2 class="menu-title">LOOKBOOK</h2>
                    <ul>
                        @if(isset($allCollections))
                            @foreach ($allCollections as $collection)
                                <li>
                                    {{-- Aquí usamos el NAME de la ruta definido en el paso 1 --}}
                                    <a href="{{ route('drops', ['idColeccion' => $collection->id_coleccion]) }}"
                                       class="{{ (isset($currentCollectionId) && $collection->id_coleccion == $currentCollectionId) ? 'active-drop-link' : '' }}">
                                        {{ $collection->Nombre }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            {{-- COLUMNA 2 Y 3: GALERÍA --}}
            @if (isset($selectedCollection) && isset($imagenes) && $imagenes->isNotEmpty())

                @php
                    $firstImg = $imagenes->first();
                    $mainSrc = '';

                    if ($firstImg && $firstImg->URL) {
                        if (str_starts_with($firstImg->URL, '../../')) {
                            $mainSrc = asset(str_replace('../../', '', $firstImg->URL));
                        } else {
                            $mainSrc = asset('storage/' . $firstImg->URL);
                        }
                    }
                @endphp

                <div class="gallery-content-area">
                    {{-- IMAGEN PRINCIPAL --}}
                    <div class="main-image-view">
                        @if($mainSrc)
                            <img id="main-drop-image"
                                 src="{{ $mainSrc }}"
                                 alt="Imagen Principal"
                                 class="drop-main-img">
                        @endif
                    </div>

                    {{-- MINIATURAS --}}
                    <div class="thumbnails-grid-area">
                        <div class="thumbnails-grid">
                            @foreach ($imagenes as $imagen)
                                @php
                                    $finalUrl = '';
                                    if ($imagen->URL) {
                                        if (str_starts_with($imagen->URL, '../../')) {
                                            $finalUrl = asset(str_replace('../../', '', $imagen->URL));
                                        } else {
                                            $finalUrl = asset('storage/' . $imagen->URL);
                                        }
                                    }
                                @endphp

                                @if($finalUrl)
                                    <div class="thumbnail-item">
                                        <img src="{{ $finalUrl }}"
                                             alt="Miniatura"
                                             data-full-src="{{ $finalUrl }}"
                                             class="thumbnail-img {{ $loop->first ? 'active-thumbnail' : '' }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="gallery-content-area" style="display: flex; justify-content: center; align-items: center; height: 50vh;">
                    <p>Selecciona una colección para ver sus fotos.</p>
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mainImage = document.getElementById('main-drop-image');
                const thumbnails = document.querySelectorAll('.thumbnail-img');

                if (mainImage && thumbnails.length > 0) {
                    thumbnails.forEach(thumbnail => {
                        thumbnail.addEventListener('click', function() {
                            const fullSrc = this.getAttribute('data-full-src');
                            // Evitar parpadeo si es la misma imagen
                            if (mainImage.src === fullSrc) return;

                            mainImage.style.opacity = '0.5';
                            setTimeout(() => {
                                mainImage.src = fullSrc;
                                mainImage.style.opacity = '1';
                            }, 150);

                            thumbnails.forEach(t => t.classList.remove('active-thumbnail'));
                            this.classList.add('active-thumbnail');
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
