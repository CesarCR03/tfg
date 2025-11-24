@extends('admin.layouts.admin')

@section('title', 'Editar Producto')

@section('content')

    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 900px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <form action="{{ route('admin.productos.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- OBLIGATORIO PARA ACTUALIZAR --}}

            {{-- DATOS BÁSICOS --}}
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Nombre:</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $producto->Nombre) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Precio (€):</label>
                    <input name="precio" value="{{ old('precio', $producto->Precio) }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Descripción:</label>
                <textarea name="descripcion" rows="4" required
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">{{ old('descripcion', $producto->Descripcion) }}</textarea>
            </div>

            {{-- SELECTORES (Pre-seleccionados) --}}
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;">Categorías:</label>
                        <select name="categorias[]" multiple style="width: 100%; height: 120px; padding: 8px; border: 1px solid #ccc;">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}"
                                    {{-- Lógica para marcar como seleccionado --}}
                                    {{ $producto->categorias->contains('id_categoria', $cat->id_categoria) ? 'selected' : '' }}>
                                    {{ $cat->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px;">Colecciones:</label>
                        <select name="colecciones[]" multiple style="width: 100%; height: 120px; padding: 8px; border: 1px solid #ccc;">
                            @foreach($colecciones as $col)
                                <option value="{{ $col->id_coleccion }}"
                                    {{ $producto->colecciones->contains('id_coleccion', $col->id_coleccion) ? 'selected' : '' }}>
                                    {{ $col->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- IMAGEN ACTUAL --}}
            <div style="margin-bottom: 25px; border: 1px solid #eee; padding: 15px; border-radius: 5px;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px;">Imagen Principal:</label>

                @if($producto->imagenes->isNotEmpty())
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <img src="{{ asset('storage/' . $producto->imagenes->first()->URL) }}"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                        <span style="color: #666; font-size: 0.9em;">Imagen actual (Sube otra abajo para cambiarla)</span>
                    </div>
                @endif

                <input type="file" name="imagen" accept="image/*" style="width: 100%;">
            </div>

            {{-- STOCK (Pre-rellenado) --}}
            @php
                // Helper rápido para sacar stock sin errores
                $getStock = fn($t) => $producto->tallas->where('talla', $t)->first()->stock ?? 0;
            @endphp

            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1em; color: #856404; border-bottom: 1px solid #ffeeba; padding-bottom: 10px;">Control de Stock</h3>
                <div style="display: flex; gap: 20px;">
                    @foreach(['S', 'M', 'L', 'unica'] as $talla)
                        <div style="flex: 1;">
                            <label style="display: block; font-weight: bold; text-align: center; margin-bottom: 5px;">{{ ucfirst($talla) }}</label>
                            <input name="stock_{{ $talla }}"
                                   value="{{ old('stock_' . $talla, $getStock($talla)) }}" min="0"
                                   style="width: 100%; text-align: center; padding: 8px; border: 1px solid #ccc;">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- BOTONES --}}
            <div style="text-align: right; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.productos.index') }}" style="color: #666; text-decoration: none;">Cancelar</a>
                <button type="submit" style="background-color: #3498db; color: white; padding: 12px 30px; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>
@endsection
