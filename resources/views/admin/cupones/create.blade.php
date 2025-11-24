@extends('admin.layouts.admin')

@section('title', 'Crear Nuevo Cupón')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <form action="{{ route('admin.cupones.store') }}" method="POST">
            @csrf

            {{-- CÓDIGO --}}
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Código del Cupón:</label>
                <input type="text" name="codigo" placeholder="Ej: VERANO2025" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; text-transform: uppercase;">
                <small style="color: #666;">Se guardará automáticamente en mayúsculas.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                {{-- TIPO --}}
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Tipo de Descuento:</label>
                    <select name="tipo" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="porcentaje">Porcentaje (%)</option>
                        <option value="fijo">Importe Fijo (€)</option>
                    </select>
                </div>

                {{-- VALOR --}}
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Valor:</label>
                    <input name="valor" placeholder="Ej: 10" required
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
            </div>

            {{-- CADUCIDAD --}}
            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Fecha de Caducidad (Opcional):</label>
                <input type="date" name="fecha_caducidad"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <small style="color: #666;">Déjalo vacío si quieres que dure para siempre.</small>
            </div>

            {{-- BOTÓN --}}
            <div style="text-align: right;">
                <button type="submit"
                        style="background-color: #27ae60; color: white; padding: 12px 30px; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">
                    Guardar Cupón
                </button>
            </div>
        </form>
    </div>
@endsection
