<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; // <-- Necesario
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // <-- Necesario
use Illuminate\Support\Facades\Auth; // <-- Necesario
use Illuminate\Support\Facades\Hash; // <-- Necesario
use Illuminate\Validation\Rules; // <-- Necesario
use Illuminate\View\View; // <-- ¡El más importante!

class RegisteredUserController extends Controller // <-- 'extends Controller' es necesario
{
    /**
     * Muestra la vista de registro.
     * ¡Este es el método que faltaba y causaba el error!
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Maneja una solicitud de registro entrante.
     * (Esta es la función que tú ya tenías)
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Nuestra modificación de rol
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false)); // Redirige a la tienda
    }
}
