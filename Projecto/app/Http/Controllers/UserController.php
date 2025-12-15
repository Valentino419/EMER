<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function logged(Request $request)
    {
        // 1. Autorización: Solo Admin puede acceder
        if (! Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
            abort(403, 'No tienes acceso a esta funcionalidad.');
        }

        $rolesPermitidos = ['user', 'inspector', 'admin'];

        // 2. Consulta Base (Excluye al usuario logueado)
        $query = User::with(['role' => fn ($q) => $q->select('id', 'name')])
            ->whereHas('role', fn ($q) => $q->whereIn('name', $rolesPermitidos))
            ->where('id', '!=', Auth::id())
            ->select('id', 'name', 'surname', 'dni', 'email', 'role_id')
            ->orderBy('surname')
            ->orderBy('name');

        // --- 3. Filtro de Usuarios Online (Optimizado) ---
        // A. Obtener IDs de TODOS los usuarios relevantes (sin paginación)
        $usersToCheckIds = User::whereHas('role', fn ($r) => $r->whereIn('name', $rolesPermitidos))
            ->where('id', '!=', Auth::id())
            ->pluck('id');

        $onlineUserIds = [];

        // B. Buscar en caché SOLO los IDs que están online
        foreach ($usersToCheckIds as $userId) {
            if (Cache::has('user-online-'.$userId)) {
                $onlineUserIds[] = $userId;
            }
        }

        // C. Aplicar el filtro a la consulta principal con WHERE IN (mucho más eficiente)
        $query->whereIn('id', $onlineUserIds);

        // ----------------------------------------------------

        // 4. Filtro de Búsqueda (Search)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $lowerSearch = strtolower($search);

            $query->where(function ($q) use ($search, $lowerSearch) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('role', fn ($r) => $r->whereRaw('LOWER(name) LIKE ?', "%{$lowerSearch}%"));
            });
        }

        // 5. Ejecución y Paginación
        $loggedUsers = $query->paginate(10);

        // 6. Post-procesamiento (Añadir nombre de rol e indicador online)
        $loggedUsers->getCollection()->transform(function ($user) {
            $user->role_name = $user->role?->name ?? 'Sin rol';
            // El indicador is_online sigue siendo útil para la vista
            $user->is_online = Cache::has('user-online-'.$user->id);

            return $user;
        });

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
        $zones = Zone::pluck('name', 'id');

        return view('user.show', compact('user', 'roles', 'zones'));
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
