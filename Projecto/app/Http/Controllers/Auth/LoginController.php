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
        return view('auth.login'); // tu vista de login
    }

    // Procesar el login
    public function login(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Regenerar sesión para seguridad
            $request->session()->regenerate();

            // Detectar el rol del usuario
            $role = Auth::user()->role->name; // Asegúrate que el campo "name" existe en la tabla roles

            // Redirigir según rol
            if ($role === 'admin') {
                return redirect()->route('dashboard-admin');
            } elseif ($role === 'inspector') {
                return redirect()->route('dashboard-inspector');
            } else {
                return redirect()->route('dashboarduser');
            }
        }

        // Si las credenciales no coinciden
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

        return redirect()->route('login');
    }
}
