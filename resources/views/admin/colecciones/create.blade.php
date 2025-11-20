@extends('admin.layouts.admin')
@section('title', 'Nueva Colección')
@section('content')
    <div style="background: white; padding: 20px; max-width: 500px;">
        <form action="{{ route('admin.colecciones.store') }}" method="POST">
            @csrf
            <label>Nombre: <input type="text" name="nombre" required style="display:block; width:100%; margin-bottom: 10px;"></label>
            <label>Año: <input type="number" name="anio" value="2025" required style="display:block; width:100%; margin-bottom: 20px;"></label>
            <button type="submit" style="background: #2980b9; color: white; padding: 8px 15px; border: none;">Crear Colección</button>
        </form>
    </div>
@endsection
