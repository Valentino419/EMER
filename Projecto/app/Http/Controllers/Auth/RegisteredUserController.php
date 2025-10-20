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
        return view('auth.register');
    }

    /**
     * Guardar un nuevo usuario en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación de todos los campos
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'dni' => 'required|integer|unique:users,dni',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Crear usuario con rol por defecto (3 = usuario normal)
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'dni' => $request->dni,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3, 
        ]);

        event(new Registered($user));

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir al dashboard del usuario
        return redirect()->route('login');
    }
}
