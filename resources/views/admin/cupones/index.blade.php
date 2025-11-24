@extends('admin.layouts.admin')

@section('title', 'Gestión de Cupones')

@section('content')
    <div style="margin-bottom: 20px; text-align: right;">
        <a href="{{ route('admin.cupones.create') }}"
           style="background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            + Crear Cupón
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
            <tr style="background-color: #f8f9fa; text-align: left;">
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Código</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Descuento</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Caducidad</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Estado</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cupones as $cupon)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><strong>{{ $cupon->codigo }}</strong></td>
                    <td style="padding: 12px;">
                        {{ $cupon->valor }}
                        {{ $cupon->tipo == 'porcentaje' ? '%' : '€' }}
                    </td>
                    <td style="padding: 12px;">
                        {{ $cupon->fecha_caducidad ? date('d/m/Y', strtotime($cupon->fecha_caducidad)) : 'Sin límite' }}
                    </td>
                    <td style="padding: 12px;">
                        @if($cupon->esValido())
                            <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 0.8em;">Activo</span>
                        @else
                            <span style="background: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 0.8em;">Caducado</span>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <div style="display: flex; align-items: center; gap: 15px;">

                            {{-- BOTÓN EDITAR --}}
                            <a href="{{ route('admin.cupones.edit', $cupon->id) }}"
                               style="color: #3498db; text-decoration: none; font-weight: bold; display: flex; align-items: center;">
                                <span>Editar</span>
                            </a>
                            <form action="{{ route('admin.cupones.send', $cupon->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Enviar este cupón a todos los usuarios?');"
                                  style="margin: 0;">
                                @csrf
                                <button type="submit"
                                        style="color: black; background: none; border: none; cursor: pointer; font-weight: bold; display: flex; align-items: center; padding: 0;">
                                    <span>Enviar</span>
                                </button>
                            </form>
                            {{-- BOTÓN ELIMINAR --}}
                            <form action="{{ route('admin.cupones.destroy', $cupon->id) }}" method="POST" onsubmit="return confirm('¿Borrar cupón?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer; font-weight: bold; display: flex; align-items: center;">
                                    <span>Eliminar</span>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
