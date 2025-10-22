<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ParkingSession;
use App\Models\Street;
use App\Models\Zone;
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

        $activeSessions = ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['car', 'street.zone'])
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
            return back()->withErrors(['car_id' => 'Selección de vehículo inválida.']);
        }

        $existingSession = ParkingSession::where('car_id', $validated['car_id'])
            ->where('status', 'active')
            ->first();
        if ($existingSession) {
            return back()->withErrors(['car_id' => 'Ya tienes un estacionamiento activo para esta patente.']);
        }

        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int)$validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle no pertenece a la zona.']);
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

                Log::info('Sesión de estacionamiento activa creada', ['id' => $session->id]);
                return $session->id;
            });

            return redirect()->back()->with('success', 'Estacionamiento iniciado correctamente.')
                ->with('sessionData', [
                    'duration' => $validated['duration'],
                    'start_time' => $validated['start_time'],
                ])->with('parkingSessionId', $sessionId);
        } catch (\Exception $e) {
            Log::error('Error al iniciar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al iniciar: ' . $e->getMessage()]);
        }
    }

    public function show($parkingSession = null)
    {
        if ($parkingSession) {
            $session = ParkingSession::where('user_id', auth()->id())
                ->where('id', $parkingSession)
                ->firstOrFail();
            if ($session->user_id !== auth()->id()) {
                abort(403, 'No tienes permiso para ver este estacionamiento.');
            }
            return view('parking.show', compact('session'));
        } else {
            $sessions = ParkingSession::where('user_id', auth()->id())->orderBy('start_time', 'desc')->get();
            return view('parking.show', compact('sessions'));
        }
    }

    public function end(Request $request, $id)
    {
        $session = ParkingSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya finalizada.',
            ], 404);
        }

        try {
            DB::transaction(function () use ($session) {
                $session->status = 'cancelled';
                $session->end_time = Carbon::now();
                $session->save();
                Log::info('Sesión de estacionamiento cancelada', ['id' => $session->id]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Estacionamiento finalizado correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cancelar sesión: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar el estacionamiento: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkActive($carId)
    {
        try {
            if (!is_numeric($carId)) {
                return response()->json([
                    'active' => false,
                    'message' => 'ID de vehículo inválido.',
                ], 400);
            }

            $activeSession = ParkingSession::where('car_id', $carId)
                ->where('user_id', auth()->id())
                ->where('status', 'active')
                ->exists();

            return response()->json([
                'active' => $activeSession,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar sesión activa: ' . $e->getMessage());
            return response()->json([
                'active' => false,
                'message' => 'Error interno al verificar la sesión activa.',
            ], 500);
        }
    }
}
