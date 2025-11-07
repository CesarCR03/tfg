<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Constructor para proteger todas las rutas de este controlador.
     */
    public function __construct()
    {
        // 1. Requerimos que el usuario haya iniciado sesión (esto lo gestiona Breeze)
        $this->middleware('auth');

        // 2. Comprobamos el ROL aquí mismo, en lugar de en las rutas.
        $this->middleware(function ($request, $next) {

            // Usamos la función isAdmin() que definimos en el modelo User.php
            if (Auth::user() && Auth::user()->isAdmin()) {
                // Si es Admin, le dejamos continuar.
                return $next($request);
            }

            // Si no es Admin, lo redirigimos a la página de inicio con un error.
            return redirect(route('home'))->with('error', 'Acceso denegado. No tienes permisos de administrador.');
        });
    }

    /**
     * Muestra la vista principal (Dashboard) del panel de administrador.
     */
    public function dashboard(): View
    {
        // De momento, solo devolvemos una vista simple.
        // Más adelante aquí pondremos estadísticas, etc.
        return view('admin.dashboard');
    }

    // --- PRÓXIMOS MÉTODOS ---
    // Aquí es donde añadiremos las funciones para gestionar productos,
    // ver usuarios, etc.
    //
    // public function gestionarProductos()
    // {
    //     return view('admin.productos.index');
    // }

}
