<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{

    public function index()
    {
        return Zone::with(['schedules, streets'])->get();
    }

     /**
      * Show the form for creating a new resource.
      */
     public function create()
    {
        $zones= Zone::all();
        return view('Zone.create', compact('zones'));
    }

     /**
      * Store a newly created resource in storage.
      */
     public function store(Request $request)
     {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'numeration' => 'required|integer',

        ]);
        return Zone::create($validated);
         
     }

     /**
      * Display the specified resource.
      */
     public function show(Zone $zone)
     {
         return $zone->load(['streets, schedules']);
     }

     /**
      * Show the form for editing the specified resource.
      */
     public function edit(string $id)
     {
         $zone= Zone::findOrFail($id);
         return view('zone.edit', compact('zone'));
     }

     /**
      * Update the specified resource in storage.
      */
     public function update(Request $request, Zone $zone)
     {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'numeration' => 'integer',
        ]);

        $zone->update($validated);
        return $zone;
    }

     /**
      * Remove the specified resource from storage.
      */
     public function destroy(Zone $zone)
     {
        $zone->delete();
        return response()->noContent();
     }

    public function checkZone(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
        ]);

        $zone = Zone::with(['streets', 'schedules'])->findOrFail($validated['zone_id']);
        return view('zones.check', compact('zone'));
    }
 }


