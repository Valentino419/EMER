<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ParkingSession;
use App\Models\Street;
use App\Models\Zone;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParkingSessionController extends Controller
{
    public function create()
    {
        $cars = Car::where('user_id', auth()->id())->get();
        $zones = Zone::all();
        $streets = Street::all();

        // Obtener todos los estacionamientos activos del usuario
        $activeSessions = ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with('car')
            ->get();

        return view('parking.create', compact('cars', 'zones', 'streets', 'activeSessions'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:30|max:1440',
            'timezone_offset' => 'required|integer',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors(['car_id' => 'Selección de auto inválida.']);
        }

        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int)$validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle no pertenece a la zona.']);
        }

        $activeSession = ParkingSession::where('user_id', auth()->id())
            ->where('car_id', $validated['car_id'])
            ->where('status', 'active')
            ->first();

        if ($activeSession) {
            return redirect()->back()->withErrors(['error' => 'Este auto ya tiene un estacionamiento activo. Finaliza el anterior primero o selecciona otro auto.']);
        }

        $zone = Zone::findOrFail($validated['zone_id']);
        $rate = $zone->getCurrentRate();

        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', - ($offsetMinutes / 60));
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString));

        $amount = ($validated['duration'] / 60) * $rate;

        try {
            $sessionId = DB::transaction(function () use ($validated, $startDateTime, $rate, $amount, $car) {
                $session = ParkingSession::create([
                    'user_id' => auth()->id(),
                    'car_id' => $validated['car_id'],
                    'street_id' => $validated['street_id'],
                    'license_plate' => $car->license_plate ?? strtoupper($car->car_plate),
                    'start_time' => $startDateTime,
                    'duration' => $validated['duration'],
                    'rate' => $rate,
                    'amount' => $amount,
                    'payment_status' => 'pending',
                    'status' => 'active',
                    'metodo_pago' => 'tarjeta',
                ]);

                Log::info('Active parking session created', ['id' => $session->id]);
                return $session->id;
            });

            return redirect()->back()->with('success', 'Estacionamiento iniciado correctamente.')
                ->with('sessionData', [
                    'duration' => $validated['duration'],
                    'start_time' => $validated['start_time'],
                ])->with('parkingSessionId', $sessionId);
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al iniciar: ' . $e->getMessage()]);
        }
    }

    public function show($parkingSession = null)
    {
        if ($parkingSession) {
            $session = ParkingSession::where('user_id', auth()->id())
                ->where('id', $parkingSession)
                ->with('car', 'street.zone')
                ->firstOrFail();
            if ($session->user_id !== auth()->id()) {
                abort(403, 'No tienes permiso para ver este estacionamiento.');
            }

            // Calcular tiempo restante si está activo
            $timeLeft = null;
            if ($session->status === 'active') {
                $start = Carbon::parse($session->start_time);
                $end = $start->copy()->addMinutes($session->duration);
                $now = Carbon::now();
                $timeLeft = max(0, $end->diffInSeconds($now));
            }

            return view('parking.show', compact('session', 'timeLeft'));
        } else {
            $sessions = ParkingSession::where('user_id', auth()->id())->orderBy('start_time', 'desc')->get();
            return view('parking.show', compact('sessions'));
        }
    }

    public function endManual($id)
    {
        $session = ParkingSession::where('user_id', auth()->id())
            ->where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return redirect()->back()->withErrors(['error' => 'No se encontró un estacionamiento activo para finalizar.']);
        }

        $session->update([
            'status' => 'cancelled',
            'end_time' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Estacionamiento cancelado correctamente.');
    }
}
