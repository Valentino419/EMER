<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Zone;
use App\Models\ParkingSession;

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

    public function end($id)
{
    $session = ParkingSession::findOrFail($id);

    $start = \Carbon\Carbon::parse($session->start_time);
    $end = now();

    $minutes = $start->diffInMinutes($end);

    // Convertimos minutos a horas, con tu regla de redondeo
    $hours = floor($minutes / 60);
    $extraMinutes = $minutes % 60;

    if ($extraMinutes > 10) {
        $hours += 1; // se pasa de 10 min, cobra otra hora
    }

    $amount = $hours * 500; // $500 por hora

    $session->end_time = $end;
    $session->amount = $amount;
    $session->save();

    return redirect()->back()->with('payment', $session);
}

}
