<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Street;
use Illuminate\Http\Request;


class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::with('streets')->get(); // Carga todas las zonas con sus calles asociadas
        return view('zone.index', compact('zones'));
    }

    public function show(Zone $zone)
    {
        $zone->load('streets'); // Carga las calles para la zona seleccionada
        return view('zone.show', compact('zone'));
    }

    public function create()
    {
        return view('zone.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Zone::create($validated);
        return redirect()->route('zone.index')->with('success', 'Zona creada exitosamente.');
    }

    // Añade edit, update, destroy si lo necesitas
}
