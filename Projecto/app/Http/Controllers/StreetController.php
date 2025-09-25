<?php

namespace App\Http\Controllers;

use App\Models\Street;
use App\Models\Zone;
use Illuminate\Http\Request;

class StreetController extends Controller
{
    public function index($zone_id = null)
    {
        $streets = $zone_id ? Street::where('zone_id', $zone_id)->get() : Street::all();
        return view('streets.index', compact('streets', 'zone_id'));
    }

    public function create(Request $request)
    {
        $zones = Zone::all();
        $zone_id = $request->query('zone_id');
        return view('streets.create', compact('zones', 'zone_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_number' => 'required|integer',
            'end_number' => 'required|integer',
            'zone_id' => 'required|exists:zones,id',
        ]);

        $street = Street::create($validated);
        return redirect()->route('zones.show', ['zone' => $validated['zone_id']])->with('success', 'Calle creada exitosamente.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Street $street)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'start_number' => 'integer',
            'end_number' => 'integer',
            'zone_id' => 'exists:zones,id',
        ]);
        $street->update($validated);
        return redirect()->route('zones.show', ['zone' => $validated['zone_id'] ?? $street->zone_id])->with('success', 'Calle actualizada exitosamente.');
    }

    public function destroy(Street $street)
    {
        $street->delete();
        return response()->noContent();
    }
}
