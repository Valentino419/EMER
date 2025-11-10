<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function logged(Request $request)
    {
        if (! Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
            abort(403, 'No tienes acceso a esta funcionalidad.');
        }

        $query = User::with(['role' => fn ($q) => $q->select('id', 'name')])
            ->whereHas('role', fn ($q) => $q->whereIn(DB::raw('LOWER(name)'), ['user', 'inspector', 'admin']))
            ->where('id', '!=', auth()->id())
            ->select('id', 'name', 'surname', 'dni', 'email', 'role_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('role', fn ($r) => $r->where(DB::raw('LOWER(name)'), 'like', '%'.strtolower($search).'%'));
            });
        }

        \Log::debug('SQL Query:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $loggedUsers = $query->paginate(25);

        $loggedUsers->getCollection()->transform(function ($user) {
            $user->role_name = $user->role?->name ?? 'Sin rol';

            return $user;
        });

        \Log::info('Usuarios encontrados por rol:', ['roles' => $loggedUsers->pluck('role_name')->countBy()]);

        $roles = Role::pluck('name', 'id');

        return view('user.logged', compact('loggedUsers', 'roles'));
    }

    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));
    }

    public function show(User $user)
    {
        $roles = Role::pluck('name', 'id');

        $user->load([
            'role',
            'cars.infractions',
            'cars.parkingSessions.zone',
            'cars.parkingSessions.street',
        ]);

        return view('user.show', compact('user', 'roles'));
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
