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
        return view('inspectors.index', compact('inspectors','users'));
    }

    public function create()
    {
        $users = User::all();
        return view('inspectors.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_number' => 'required|string|max:255',
        ]);

        Inspectors::create($request->all());

        return redirect()->route('inspectors.index')->with('success', 'Inspector creado correctamente.');
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
