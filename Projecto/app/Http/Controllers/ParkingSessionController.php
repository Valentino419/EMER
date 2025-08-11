<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Zone;

class ParkingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::all();
        $zones = Zone::all();

        return view('parking.create', compact('cars', 'zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'estimated_minutes' => 'required|integer|min:15',
        ]);

        ParkingSession::create([
            'car_id' => $request->car_id,
            'zone_id' => $request->zone_id,
            'start_time' => now(),
            'estimated_end_time' => now()->addMinutes($request->estimated_minutes),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', '¡Estacionamiento iniciado correctamente!');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
