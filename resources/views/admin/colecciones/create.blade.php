@extends('admin.layouts.admin')
@section('title', 'Nueva Colección')
@section('content')
    <div style="background: white; padding: 20px; max-width: 500px;">
        <form action="{{ route('admin.colecciones.store') }}"
              method="POST"
              enctype="multipart/form-data">   {{-- ← OBLIGATORIO --}}
            @csrf

            <label>Nombre:</label>
            <input type="text" name="nombre" required style="display:block; width:100%; margin-bottom: 10px;">

            <label>Año:</label>
            <input type="number" name="anio" value="2025" required style="display:block; width:100%; margin-bottom: 20px;">

            <label style="font-weight: bold;">Imagen (Opcional):</label>
            <input type="file" name="imagen_coleccion" accept="image/*">

            <button type="submit" style="background: #2980b9; color: white; padding: 8px 15px; border: none;">
                Crear Colección
            </button>
        </form>

    </div>
@endsection
