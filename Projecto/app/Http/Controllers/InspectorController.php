<?php

namespace App\Http\Controllers;

use App\Models\Inspectors;
use App\Models\User;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    public function index()
    {
        $inspectors = Inspectors::with('user')->get();
        $users = User::orderBy('name')->get();
        return view('inspector.index', compact('inspectors','users'));
    }

    public function create()
    {
        $users = User::all();
        return view('inspector.create', compact('users'));
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

    User::create($validated);

    return redirect()->route('inspectors.index')->with('success', 'Inspector creado con éxito.');
}

    public function edit(Inspectors $inspector)
    {
        $users = User::all();
        return view('inspectors.edit', compact('inspector', 'users'));
    }

    public function update(Request $request, Inspectors $inspector)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_number' => 'required|string|max:255',
        ]);

        $inspector->update($request->all());

        return redirect()->route('inspectors.index')->with('success', 'Inspector actualizado correctamente.');
    }

    public function destroy(Inspectors $inspector)
    {
        $inspector->delete();

        return redirect()->route('inspectors.index')->with('success', 'Inspector eliminado correctamente.');
    }
}
