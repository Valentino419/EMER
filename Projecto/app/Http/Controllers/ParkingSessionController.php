<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Zone;
use App\Models\ParkingSession;
use App\Models\Street;

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
        $streets= Street::all();
        $zones = Zone::all();

        return view('parking.create', compact('cars', 'zones','streets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'street_id' => 'required|exists:streets,id',
            'zone_id' => 'required|exists:zones,id',
            'duration' => 'required|integer|min:15',
            'rate' => 'required|numeric',
            'status' => 'required|in:active,completed',
        ]);

        ParkingSession::create([
            'car_id' => $validated['car_id'],
            'street_id' => $validated['street_id'],
            'start_time' =>  $validated['start_time'],
            'end_time' =>  $validated['end_time'],
            'rate' => $validated['rate'],
            'duration' => $validated['duration'],
            'status' => $validated['status'],
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
