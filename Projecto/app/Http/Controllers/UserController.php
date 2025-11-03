<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function logged(Request $request)
{
    // Verificar que el usuario es admin
    if (!Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
        abort(403, 'No tienes acceso a esta funcionalidad.');
    }

    // Construir la consulta con el Builder (sin ejecutar aún)
    $query = User::with('role')
        ->whereHas('role', function ($q) {
            $q->whereIn('name', ['user', 'inspector', 'admin']);
        })
        ->where('id', '!=', auth()->id());

    // Aplicar filtro de búsqueda si existe
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('surname', 'like', "%{$search}%")
              ->orWhere('dni', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhereHas('role', function ($r) use ($search) {
                  $r->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Ejecutar la consulta con paginación (recomendado)
    $loggedUsers = $query->select('id', 'name', 'email', 'role_id')
        ->paginate(25); // o get() si no quieres paginación

    // Añadir el nombre del rol (solo si usas get(), no paginate)
    // Si usas paginate(), el map se aplica así:
    $loggedUsers->getCollection()->transform(function ($user) {
        $user->role_name = $user->role?->name ?? 'Sin rol';
        return $user;
    });

    \Log::info('Usuarios con rol user, inspector o admin:', [
        'users' => $loggedUsers->toArray()
    ]);

    return view('user.logged', compact('loggedUsers'));
}

    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    public function store(Request $request)
    {
        // Verificar que el usuario es admin
        if (! Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
            abort(403, 'No tienes acceso a esta funcionalidad.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('user.logged')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        // Verificar que el usuario es admin
        if (! Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
            abort(403, 'No tienes acceso a esta funcionalidad.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($validated);

        return redirect()->route('user.logged')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        // Verificar que el usuario es admin
        if (! Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
            abort(403, 'No tienes acceso a esta funcionalidad.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.logged')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function showUserZones()
    {
        $user = auth()->user();
        if (! $user || strtolower($user->role->name ?? '') !== 'user') {
            abort(403, 'No tienes permiso para ver esta página.');
        }
    }
}
