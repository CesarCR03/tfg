@extends('admin.layouts.admin')

@section('title', 'Crear Nuevo Producto')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 800px;">

        {{-- 1. MOSTRAR ERRORES DE VALIDACIÓN --}}
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nombre, Descripción y Precio (Igual que antes) --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="font-weight: bold;">Nombre:</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required style="width: 100%; padding: 8px; border: 1px solid #ddd;">
                </div>
                <div>
                    <label style="font-weight: bold;">Precio (€):</label>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" required style="width: 100%; padding: 8px; border: 1px solid #ddd;">
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Descripción:</label>
                <textarea name="descripcion" rows="3" required style="width: 100%; padding: 8px; border: 1px solid #ddd;">{{ old('descripcion') }}</textarea>
            </div>

            {{-- 2. NUEVOS SELECTORES DE CATEGORÍA Y COLECCIÓN --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; background: #f0f4f8; padding: 15px; border-radius: 5px;">

                {{-- Categorías --}}
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Categorías (Mantén Ctrl para varias):</label>
                    <select name="categorias[]" multiple style="width: 100%; height: 100px; padding: 5px;">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id_categoria }}">{{ $cat->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Colecciones --}}
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <label style="font-weight: bold;">Colecciones:</label>
                        {{-- Enlace rápido para crear colección --}}
                        <a href="{{ route('admin.colecciones.create') }}" style="font-size: 0.8em; color: #2980b9;">+ Nueva Colección</a>
                    </div>
                    <select name="colecciones[]" multiple style="width: 100%; height: 100px; padding: 5px;">
                        @foreach($colecciones as $col)
                            <option value="{{ $col->id_coleccion }}">{{ $col->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Imagen y Stock (Igual que antes) --}}
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold;">Imagen:</label>
                <input type="file" name="imagen" accept="image/*" required>
            </div>

            <h3 style="border-bottom: 1px solid #eee; padding-bottom: 5px;">Inventario</h3>
            <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                <label>S: <input type="number" name="stock_s" value="{{ old('stock_s') }}" style="width: 60px;"></label>
                <label>M: <input type="number" name="stock_m" value="{{ old('stock_m') }}" style="width: 60px;"></label>
                <label>L: <input type="number" name="stock_l" value="{{ old('stock_l') }}" style="width: 60px;"></label>
                <label>Única: <input type="number" name="stock_unica" value="{{ old('stock_unica') }}" style="width: 60px;"></label>
            </div>

            <button type="submit" style="background-color: #27ae60; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px;">Guardar Producto</button>
        </form>
    </div>
@endsection
