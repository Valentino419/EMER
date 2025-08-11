<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        dd('test');
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        // Validación
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Intentar autenticación
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard'); // o donde quieras redirigir
        }

        // Si falla
        throw ValidationException::withMessages([
            'email' => __('Las credenciales no son correctas.'),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
