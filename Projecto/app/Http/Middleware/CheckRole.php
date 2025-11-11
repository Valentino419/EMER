<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Lista de roles permitidos (separados por coma en la ruta)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        $user = Auth::user();

        // 2. Verificar que el usuario tenga setting y role cargados
        if (!$user->setting || !$user->role) {
            abort(403, 'Tu cuenta no está configurada correctamente. Contacta al administrador.');
        }

        // 3. Obtener el nombre del rol (con relación cargada)
        $userRole = $user->role->name;

        // 4. Convertir roles permitidos a array y verificar
        $allowedRoles = is_array($roles) ? $roles : explode(',', $roles[0] ?? '');

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Todo OK → continuar
        return $next($request);
    }
}
