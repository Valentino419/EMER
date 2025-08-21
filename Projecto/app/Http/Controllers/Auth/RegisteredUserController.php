<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar formulario de registro (Blade).
     */
    public function create()
    {
        return view('register');
    }


    /**
     * Guardar un nuevo usuario en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // 👈 acá podés asignar un rol por defecto (ej: usuario normal)
        ]);

        event(new Registered($user));

        Auth::login($user); // inicia sesión automáticamente

        return redirect()->route('dashboard'); // redirige al dashboard
    }
}
