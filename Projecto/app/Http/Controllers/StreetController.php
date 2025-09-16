<?php

namespace App\Http\Controllers;

use App\Models\Street;
use Illuminate\Http\Request;

class StreetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Street::with('zone')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_number' => 'required|integer',
            'end_number' => 'required|integer',
            'zone_id' => 'required|exists:zones,id',
        ]);

        return Street::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Street $street)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'start_number' => 'integer',
            'end_number' => 'integer',
            'zone_id' => 'exists:zones,id',
        ]);
        $street->update($validated);
        return $street;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Street $street)
    {
        $street->delete();
        return response()->noContent();
    }
}
