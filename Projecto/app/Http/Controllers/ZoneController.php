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
        if (Auth::check() && Auth::user()->role === 'user') {
            $zones = Auth::user()->zones()->with('streets')->get(); // Solo zonas asignadas al usuario
        } else {
            $zones = Zone::with('streets')->get(); // Todas las zonas para admin/inspector
        }
        return view('zone.index', compact('zones'));
    }

    /**
     * Display the specified zone based on user role.
     */
    public function show(Zone $zone)
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            // Verificar que la zona pertenece al usuario
            if (!Auth::user()->zones()->where('id', $zone->id)->exists()) {
                abort(403, 'No tienes acceso a esta zona.');
            }
        }

        $zone->load('streets'); // Carga las calles para la zona seleccionada
        return view('zone.show', compact('zone'));
    }

    /**
     * Show the form for creating a new zone.
     */
    public function create()
    {
        return view('zone.create');
    }

    /**
     * Store a newly created zone in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate'=> 'required|integer|max:255'
        ]);

        Zone::create($validated);
        return redirect()->route('zone.index')->with('success', 'Zona creada exitosamente.');
    }

    /**
     * Show the form for editing the specified zone.
     */
    public function edit(Zone $zone)
    {
        return view('zone.edit', compact('zone'));
    }

    /**
     * Remove the specified zone from storage.
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('zone.index')->with('success', 'Zona eliminada exitosamente.');
    }
}