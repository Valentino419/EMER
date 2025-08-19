<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictToRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        $userRole = $user->role ? $user->role->name : 'user';

        if ($userRole !== $role) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}