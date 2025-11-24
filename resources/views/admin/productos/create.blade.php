@extends('admin.layouts.admin')

@section('title', 'Crear Nuevo Producto')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 900px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- FILA 1: NOMBRE Y PRECIO --}}
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Nombre del Producto:</label>
                    <input type="text"
                           name="nombre"
                           value="{{ old('nombre') }}"
                           required
                           placeholder="Ej: Camiseta Oversize"
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Precio (€):</label>
                    <input name="precio"
                           value="{{ old('precio') }}"
                           required
                           placeholder="0.00"
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                </div>
            </div>

            {{-- FILA 2: DESCRIPCIÓN --}}
            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Descripción:</label>
                <textarea name="descripcion"
                          rows="4"
                          required
                          placeholder="Detalles del producto..."
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; resize: vertical;">{{ old('descripcion') }}</textarea>
            </div>

            {{-- FILA 3: SELECTORES (CATEGORÍA Y COLECCIÓN) --}}
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 25px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

                    {{-- COLUMNA IZQUIERDA --}}
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 8px; color: #444;">Categorías:</label>
                        <select name="categorias[]" multiple style="width: 100%; height: 120px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}">{{ $cat->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div>
                        {{-- AQUI FALTABA EL LABEL --}}
                        <label style="font-weight: bold; display: block; margin-bottom: 8px; color: #444;">Colecciones:</label>
                        <select name="colecciones[]" multiple style="width: 100%; height: 120px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            @foreach($colecciones as $col)
                                <option value="{{ $col->id_coleccion }}">{{ $col->Nombre }}</option>
                            @endforeach
                        </select>
                        <small style="color: #666; font-size: 0.8em;">Selecciona a qué Drop pertenece.</small>
                    </div>
                </div>
            </div>

            {{-- FILA 4: IMAGEN --}}
            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Imagen Principal:</label>
                <input type="file"
                       name="imagen"
                       accept="image/*"
                       style="padding: 10px; background: white; border: 1px dashed #ccc; width: 100%; border-radius: 5px;">
            </div>

            {{-- FILA 5: INVENTARIO (Mejorado) --}}
            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border: 1px solid #ffeeba; margin-bottom: 25px;">
                <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1em; color: #856404; border-bottom: 1px solid #ffeeba; padding-bottom: 10px;">
                    Control de Stock
                </h3>

                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    {{-- Bloque Talla S --}}
                    <div style="flex: 1; min-width: 80px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; text-align: center;">Talla S</label>
                        <input name="stock_s" value="{{ old('stock_s', 0) }}" min="0"
                               style="width: 100%; text-align: center; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    {{-- Bloque Talla M --}}
                    <div style="flex: 1; min-width: 80px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; text-align: center;">Talla M</label>
                        <input name="stock_m" value="{{ old('stock_m', 0) }}" min="0"
                               style="width: 100%; text-align: center; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    {{-- Bloque Talla L --}}
                    <div style="flex: 1; min-width: 80px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; text-align: center;">Talla L</label>
                        <input name="stock_l" value="{{ old('stock_l', 0) }}" min="0"
                               style="width: 100%; text-align: center; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    {{-- Bloque Única --}}
                    <div style="flex: 1; min-width: 80px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; text-align: center;">Única</label>
                        <input name="stock_unica" value="{{ old('stock_unica', 0) }}" min="0"
                               style="width: 100%; text-align: center; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                </div>
            </div>

            {{-- BOTÓN --}}
            <div style="text-align: right;">
                <button type="submit"
                        style="background-color: #27ae60; color: white; padding: 12px 30px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold; transition: background 0.3s;">
                    Guardar Producto
                </button>
            </div>

        </form>
    </div>
@endsection
