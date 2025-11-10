<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    /**
     * Display a listing of zones based on user role.
     */
    public function index()
    {
        if (Auth::check() && Auth::user()->role?->name === 'user') {
            $zones = Auth::user()->zones()->with('streets')->get();
        } else {
            $zones = Zone::with('streets')->get();
        }
        return view('zones.index', compact('zones'));
    }

    /**
     * Display the specified zone based on user role.
     */
    public function show(Zone $zone)
    {
        if (Auth::check() && Auth::user()->role?->name === 'user') {
            if (!Auth::user()->zones()->where('id', $zone->id)->exists()) {
                abort(403, 'No tienes acceso a esta zona.');
            }
        }

        $zone->load('streets'); // Carga las calles para la zona seleccionada
        return view('zones.show', compact('zone'));
    }

    /**
     * Show the form for creating a new zones.
     */
    public function create()
    {
        return view('zones.create');
    }

    /**
     * Store a newly created zone in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0', // Cambiado a numeric
        ]);

        Zone::create($validated);

        return redirect()->route('zones.index')->with('success', 'Zona creada exitosamente.');
    }

    /**
     * Show the form for editing the specified zones.
     */
    public function edit(Zone $zone)
    {
        return view('zone.edit', compact('zone'));
    }

    /**
     * Update the specified zone in storage.
     * ¡¡¡AQUÍ ESTABA EL PROBLEMA!!!
     */
    public function update(Request $request, Zone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0', // Acepta decimales (ej: 150.50)
        ]);

        $zone->update($validated);

        return redirect()->route('zones.index')->with('success', 'Zona actualizada correctamente.');
    }

    /**
     * Remove the specified zone from storage.
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('zones.index')->with('success', 'Zona eliminada exitosamente.');
    }
}
