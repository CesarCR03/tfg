@extends('admin.layouts.admin')

@section('title', 'Editar Cupón')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <form action="{{ route('admin.cupones.update', $cupon->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- CÓDIGO --}}
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Código del Cupón:</label>
                <input type="text" name="codigo"
                       value="{{ old('codigo', $cupon->codigo) }}"
                       required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; text-transform: uppercase;">
                <small style="color: #666;">Solo letras y números (Sin acentos ni espacios).</small>
                @error('codigo')
                <p style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                {{-- TIPO --}}
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Tipo de Descuento:</label>
                    <select name="tipo" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="porcentaje" {{ $cupon->tipo == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                        <option value="fijo" {{ $cupon->tipo == 'fijo' ? 'selected' : '' }}>Importe Fijo (€)</option>
                    </select>
                </div>

                {{-- VALOR --}}
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Valor:</label>
                    <input name="valor"
                           value="{{ old('valor', $cupon->valor) }}"
                           required
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
            </div>

            {{-- CADUCIDAD --}}
            <div style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #333;">Fecha de Caducidad:</label>
                <input type="date" name="fecha_caducidad"
                       value="{{ old('fecha_caducidad', $cupon->fecha_caducidad) }}"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            {{-- BOTONES --}}
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.cupones.index') }}" style="color: #666; text-decoration: none;">Cancelar</a>
                <button type="submit"
                        style="background-color: #3498db; color: white; padding: 12px 30px; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">
                    Actualizar Cupón
                </button>
            </div>
        </form>
    </div>
@endsection
