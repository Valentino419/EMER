<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Zone;
use App\Models\Street;
use App\Models\ParkingSession;
use Illuminate\Support\Carbon;

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
        DD($request->all());
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
            'rate' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,completed',
            'timezone_offset' => 'required|integer', // New: Validate offset
        ]);

        // Get client's timezone offset in hours (JS getTimezoneOffset() is minutes, positive for west of UTC)
        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', - ($offsetMinutes / 60)); // e.g., 180 minutes (UTC-3) -> '-03:00'

        // Parse start_time as client's local time, using client's local today
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString)); // Use client's current date

        // Optional: Convert to UTC for storage if your DB uses UTC
        // $startDateTime = $startDateTime->utc();

        // Verify street belongs to selected zone
        $street = \App\Models\Street::find($validated['street_id']);
        if ($street->zone_id !== (int) $validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle seleccionada no pertenece a la zona seleccionada.']);
        }

        // Create parking record
        ParkingSession::create([
            'car_id' => $validated['car_id'],
            'zone_id' => $validated['zone_id'],
            'street_id' => $validated['street_id'],
            'start_time' => $startDateTime,
            'duration' => $validated['duration'],
            'rate' => $validated['rate'],
            'status' => $validated['status'] ?? 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'Estacionamiento registrado correctamente.');
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
