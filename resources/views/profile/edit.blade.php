{{-- Usamos tu plantilla principal --}}
@extends('layouts.app')

{{-- Definimos el título --}}
@section('title', 'Mi Perfil')

{{-- Definimos el contenido --}}
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. PANEL DE CONTROL (Bienvenida y botones) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Hola, {{ Auth::user()->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ Auth::user()->email }}
                    </p>
                </div>

                <div class="flex gap-4">
                    {{-- Botón Admin (Solo visible para administradores) --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 transition ease-in-out duration-150">
                            Panel Admin
                        </a>
                    @endif

                    {{-- Botón Cerrar Sesión --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. HISTORIAL DE PEDIDOS --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Historial de Pedidos') }}
                        </h2>
                    </header>

                    @if(isset($pedidos) && $pedidos->isNotEmpty())
                        <div class="mt-6 space-y-6">
                            @foreach($pedidos as $pedido)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-gray-800 dark:text-gray-200">Pedido #{{ $pedido->id }}</span>
                                        <span class="text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y') }}</span>
                                    </div>

                                    <ul class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        @foreach($pedido->detalles as $detalle)
                                            <li>
                                                {{ $detalle->cantidad }}x {{ $detalle->nombre_producto }} ({{ $detalle->talla }})
                                                - {{ number_format($detalle->precio_unitario, 2) }}€
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="text-right font-bold text-gray-900 dark:text-gray-100">
                                        Total: {{ number_format($pedido->total, 2) }}€
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-gray-500 dark:text-gray-400">Aún no has realizado ningún pedido.</p>
                    @endif
                </div>
            </div>

            {{-- 3. DATOS DE LA CUENTA (Formularios de Breeze) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
