@extends('admin.layouts.admin')

@section('title', 'Gestión de Colecciones')

@section('content')
    <div style="margin-bottom: 20px; text-align: right;">
        <a href="{{ route('admin.colecciones.create') }}" style="background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            + Nueva Colección
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
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">ID</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Portada</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Nombre</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Año</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($colecciones as $coleccion)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">{{ $coleccion->id_coleccion }}</td>
                    <td style="padding: 12px;">
                        @if($coleccion->imagen_url)
                            <img src="{{ asset('storage/' . $coleccion->imagen_url) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="padding: 12px;"><strong>{{ $coleccion->Nombre }}</strong></td>
                    <td style="padding: 12px;">{{ $coleccion->Año }}</td>
                    <td style="padding: 12px;">
                        {{-- Contenedor Flex para alinear los botones --}}
                        <div style="display: flex; align-items: center; gap: 15px;">

                            {{-- BOTÓN EDITAR (Azul) --}}
                            <a href="{{ route('admin.colecciones.edit', $coleccion->id_coleccion) }}"
                               style="color: #3498db; text-decoration: none; font-weight: bold; display: flex; align-items: center;">
                                {{-- Icono opcional si usas FontAwesome, si no, solo texto --}}
                                <span>Editar</span>
                            </a>

                            {{-- BOTÓN ELIMINAR (Rojo) --}}
                            <form action="{{ route('admin.colecciones.destroy', $coleccion->id_coleccion) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Borrar colección? Los productos asociados NO se borrarán, solo se desvincularán.');"
                                  style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="color: #e74c3c; background: none; border: none; cursor: pointer; text-decoration: none; font-weight: bold; display: flex; align-items: center;">
                                    <span>Eliminar</span>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $colecciones->links() }}
        </div>
    </div>
@endsection
