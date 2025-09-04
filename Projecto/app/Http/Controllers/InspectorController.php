<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inspectors;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::with('role') // Eager-load the role relationship
            ->whereHas('role', function ($q) {
                $q->where('name', 'inspector');
            })
            ->orderBy('name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $inspectors = $query->paginate(10)->appends(['search' => $search]);
        $roles = Role::pluck('name', 'id');

        return view('inspector.index', compact('inspectors', 'roles'));
    }

    public function create()
    {
        return view('inspector.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Ensures password matches password_confirmation
            'role_id' => 'required|in:1,2,3',
        ]);

        // Hash the password before storing
        $validated['password'] = bcrypt($validated['password']);
        try {
            User::create($validated);
            return redirect()->route('inspectors.index')->with('success', 'Inspector creado con éxito.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el inspector.'])->withInput();
        }


        return redirect()->route('inspectors.index')->with('success', 'Inspector creado con éxito.');
    }

    public function edit(Inspectors $inspector)
    {
        $users = User::all();
        return view('inspector.edit', compact('inspector', 'users'));
    }

    public function update(Request $request, User $inspector)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni,' . $inspector->id,
            'email' => 'required|email|max:255|unique:users,email,' . $inspector->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'surname.required' => 'El apellido es obligatorio.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'El DNI ya está registrado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role_id.required' => 'El rol es obligatorio.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ]);

        try {
            DB::beginTransaction();

            // Only update password if provided
            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']); // Remove password from update if not provided
            }

            $inspector->update($validated);

            DB::commit();

            return redirect()->route('inspectors.index')->with('success', 'Inspector actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating inspector: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error' => 'Error al actualizar el inspector. Por favor, intenta de nuevo.'])->withInput();
        }
    }

    public function destroy(User $inspector)
    {
        $inspector->delete();

        return redirect()->route('inspectors.index')->with('success', 'Inspector eliminado correctamente.');
    }
}
