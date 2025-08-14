<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);

        //Verificar si el usuario esta autenticado
        if(!Auth::check()){
            return redirect('login')->with('error', 'Debes iniciar sesion');
        }
        $user= Auth::user();

        //verificar si el usuario tiene una config y rol asignado:
        if(!$user->setting ||!$user->settings->role){
            abort(403, 'No tienes un rol asignado.');
        }

        // Obtener el nombre del rol
        $userRole = $user->settings->role->name;

        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (!in_array($userRole, $roles)) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
