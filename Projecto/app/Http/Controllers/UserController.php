<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    <?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function logged()
    {
        // Verificar que el usuario es admin
        if (!Auth::check() || strtolower(Auth::user()->role->name ?? '') !== 'admin') {
            abort(403, 'No tienes acceso a esta funcionalidad.');
        }

        // Obtener sesiones activas con user_id
        $activeSessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime')))
            ->get();

        // Obtener usuarios asociados a las sesiones activas
        $loggedUsers = User::whereIn('id', $activeSessions->pluck('user_id'))
            ->get(['id', 'name', 'email', 'role_id'])
            ->map(function ($user) {
                $user->role_name = $user->role ? $user->role->name : 'Sin rol';
                return $user;
            });

        \Log::info('Usuarios logueados:', ['users' => $loggedUsers->toArray()]);

        return view('users.logged', compact('loggedUsers'));
    
    }


   public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('User.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->type->name,
            'surname' => $request->type->surname,
            'dni' => $request->type->dni,
            'dni' => $request->type->dni,
            'email' => $request->type->email,
            'password' => $request->type->password,
            'role'=>$request->type->role
        ]);

        return redirect()->route('user.index');
    }

    
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'dni' => 'required|string|unique:users,dni,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::find($id);
        $user->update($validated);
        return redirect()->route('user.index');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index');
    }

    public function showUserZones()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'user') {
            abort(403, 'No tienes permiso para ver esta página.');
        }

        $zones = $user->zones()->with('streets')->get();
        return view('zones.index', compact('zones'));
    }

    /**
     * Show streets for a zone assigned to the authenticated user.
     */
    public function showUserStreets($zoneId)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'user') {
            abort(403, 'No tienes permiso para ver esta página.');
        }

        // Verificar que la zona pertenece al usuario
        $zone = $user->zones()->where('id', $zoneId)->first();
        if (!$zone) {
            abort(403, 'No tienes acceso a esta zona.');
        }

        $streets = $zone->streets;
        return view('street.index', compact('streets', 'zone_id' => $zoneId));
    }
}
