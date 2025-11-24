@extends('admin.layouts.admin')

@section('title', 'Gestión de Productos')

@section('content')
    <div style="margin-bottom: 20px; text-align: right;">
        {{-- Enlace al formulario de crear --}}
        <a href="{{ route('admin.productos.create') }}" style="background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            + Nuevo Producto
        </a>
    </div>

    {{-- Mensaje de Éxito --}}
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
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Imagen</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Nombre</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Precio</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Stock</th>
                <th style="padding: 12px; border-bottom: 2px solid #ddd;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($productos as $producto)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">{{ $producto->id_producto }}</td>

                    {{-- COLUMNA IMAGEN (Protegida contra errores) --}}
                    <td style="padding: 12px;">
                        @if($producto->imagenes && $producto->imagenes->isNotEmpty())
                            {{-- Probamos a mostrar la imagen --}}
                            <img src="{{ asset('storage/' . $producto->imagenes->first()->URL) }}"
                                 alt="Img"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span style="color: #999; font-size: 0.8em;">Sin Imagen</span>
                        @endif
                    </td>

                    <td style="padding: 12px;"><strong>{{ $producto->Nombre }}</strong></td>
                    <td style="padding: 12px;">{{ number_format($producto->Precio, 2) }}€</td>

                    {{-- COLUMNA STOCK (Protegida) --}}
                    <td style="padding: 12px;">
                        @if($producto->tallas)
                            {{ $producto->tallas->sum('stock') }}
                        @else
                            0
                        @endif
                    </td>

                    <td style="padding: 12px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            {{-- Editar --}}
                            <a href="{{ route('admin.productos.edit', $producto->id_producto) }}"
                               style="color: #3498db; text-decoration: none; font-weight: bold; display: flex; align-items: center;">
                                <span>Editar</span>
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('admin.productos.destroy', $producto->id_producto) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar producto?');"
                                  style="margin: 0;">
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

        <div style="margin-top: 20px;">
            {{ $productos->links() }}
        </div>
    </div>
@endsection
