<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthenticatedSessionController extends Controller
{
    
    
    public function create(Request $request)
    {
        
    if (Auth::check()) {
       
        return redirect()->route('dashboard');
    }
     return view('auth.login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

    $request->session()->regenerate();

    
    if (auth()->user()->hasVerifiedEmail()) {
        return redirect()->intended('/dashboard');
    }

    // Si no verificó → lo sacamos y lo mandamos a verificar
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('verification.notice')
        ->with('status', 'Por favor verifica tu correo antes de entrar.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
