<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autenticar usuario
        $request->authenticate();

        // Regenerar sesión por seguridad
        $request->session()->regenerate();

        // Obtener el rol del usuario
        $role = Auth::user()->role->name ?? null;

        // Redirigir según rol
        if ($role->name === 'admin') {
            return redirect()->intended(route('dashboard.admin'));
        } elseif ($role->name === 'inspector') {
            return redirect()->intended(route('dashboard.inspector'));
        } else {
            return redirect()->intended(route('dashboard.user'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
