@extends('admin.layouts.admin')

@section('title', 'Editar Colección')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <form action="{{ route('admin.colecciones.update', $coleccion->id_coleccion) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- ¡Importante para editar! --}}

            {{-- NOMBRE --}}
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Nombre:</label>
                <input type="text"
                       name="nombre"
                       value="{{ old('nombre', $coleccion->Nombre) }}"
                       required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
            </div>

            {{-- AÑO --}}
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Año:</label>
                <input
                       name="anio"
                       value="{{ old('anio', $coleccion->Año) }}"
                       required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
            </div>

            {{-- IMAGEN ACTUAL --}}
            <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px; color: #333;">Portada Actual:</label>

                @if($coleccion->imagen_url)
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <img src="{{ asset('storage/' . $coleccion->imagen_url) }}"
                             alt="Portada actual"
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                        <span style="color: #27ae60; font-size: 0.9em;">✔ Imagen asignada</span>
                    </div>
                @else
                    <span style="color: #e74c3c;">⚠ No hay imagen asignada</span>
                @endif
            </div>

            {{-- SUBIR NUEVA IMAGEN --}}
            <div style="margin-bottom: 30px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Cambiar Imagen:</label>
                <input type="file"
                       name="imagen_coleccion"
                       accept="image/*"
                       style="padding: 10px; background: white; border: 1px dashed #ccc; width: 100%; border-radius: 5px;">
                <small style="color: #666;">Si subes una foto nueva, la anterior se borrará.</small>
            </div>

            {{-- BOTONES --}}
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.colecciones.index') }}" style="color: #666; text-decoration: none;">Cancelar</a>

                <button type="submit"
                        style="background-color: #3498db; color: white; padding: 12px 30px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold;">
                    Actualizar Colección
                </button>
            </div>

        </form>
    </div>
@endsection
