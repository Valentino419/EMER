<?php

namespace App\Http\Controllers;

use App\Models\Street;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreetController extends Controller
{
    /**
     * Display a listing of streets based on user role and zones.
     */
    public function index($zone_id = null)
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            if ($zone_id && !Auth::user()->zones()->where('id', $zone_id)->exists()) {
                abort(403, 'No tienes acceso a esta zona.');
            }
            $streets = $zone_id ? Street::where('zone_id', $zone_id)->get() : collect();
        } else {
            $streets = $zone_id ? Street::where('zone_id', $zone_id)->get() : Street::all();
        }
        return view('streets.index', compact('streets', 'zone_id'));
    }

    /**
     * Show the form for creating a new street.
     */
    public function create(Request $request)
    {
        $zones = Zone::all();
        $zone_id = $request->query('zone_id');
        return view('streets.create', compact('zones', 'zone_id'));
    }

    /**
     * Store a newly created street in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_street' => 'required|string|max:255',
            'end_street' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
            'start_lat' => 'nullable|numeric|between:-90,90',
            'start_lng' => 'nullable|numeric|between:-180,180',
            'end_lat' => 'nullable|numeric|between:-90,90',
            'end_lng' => 'nullable|numeric|between:-180,180',
        ]);

        $street = Street::create($validated);
        return redirect()->route('zones.show', ['zone' => $validated['zone_id']])->with('success', 'Calle creada exitosamente.');
    }

    /**
     * Display the specified street.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified street.
     */
    public function edit(string $id)
    {
        $street = Street::findOrFail($id);
        $zones = Zone::all();
        return view('streets.edit', compact('street', 'zones'));
    }

    /**
     * Update the specified street in storage.
     */
    public function update(Request $request, Street $street)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'start_street' => 'string|max:255',
            'end_street' => 'string|max:255',
            'zone_id' => 'exists:zones,id',
            
        ]);
        $street->update($validated);
        return redirect()->route('zones.show', ['zone' => $validated['zone_id'] ?? $street->zone_id])->with('success', 'Calle actualizada exitosamente.');
    }

    /**
     * Remove the specified street from storage.
     */
    public function destroy(Street $street)
    {
        $zone_id = $street->zone_id;
        $street->delete();
        return redirect()->route('zones.show', ['zone' => $zone_id])->with('success', 'Calle eliminada exitosamente.');
    }
}
