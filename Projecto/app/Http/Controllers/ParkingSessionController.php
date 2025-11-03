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

        // Expirar sesiones vencidas automáticamente
        ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereRaw('end_time <= ?', [now()])
            ->update(['status' => 'expired']);

        // Cargar sesiones activas
        $activeSessions = ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['car', 'zone', 'street'])
            ->get();

        return view('parking.create', compact('cars', 'zones', 'streets', 'activeSessions'));
    }

   /* public function store(Request $request)
    {
        Log::info('Entrando a store', ['request' => $request->all()]);

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|in:60,120,180,240,360,480',
            'timezone_offset' => 'required|integer',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors(['car_id' => 'Vehículo inválido.']);
        }

        if (ParkingSession::where('car_id', $validated['car_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->exists()
        ) {
            return back()->withErrors(['car_id' => 'Ya tienes un estacionamiento activo.']);
        }

        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int)$validated['zone_id']) {
            return back()->withErrors(['street_id' => 'Calle no válida.']);
        }

        $zone = Zone::findOrFail($validated['zone_id']);
        $rate = $zone->rate ?? 5.0;

        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', - ($offsetMinutes / 60));
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString));
        $durationInMinutes = (int)$validated['duration'];
        $endDateTime = $startDateTime->copy()->addMinutes($durationInMinutes);
        $amount = ($durationInMinutes / 60) * $rate;

        // CREAR LA SESIÓN EN LA BASE DE DATOS
        $parkingSession = ParkingSession::create([
            'car_id' => $validated['car_id'],
            'zone_id' => $validated['zone_id'],
            'street_id' => $validated['street_id'],
            'user_id' => auth()->id(),
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'duration' => $durationInMinutes,
            'amount' => $amount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'metodo_pago' => 'tarjeta',
            'license_plate' => $car->license_plate ?? $car->car_plate ?? 'N/A',
        ]);

        Log::info('Sesión creada', ['session_id' => $parkingSession->id]);

        // GUARDAR EN SESIÓN PARA EL PAGO
        session([
            'parking_session_id' => $parkingSession->id,
            'parking_amount' => $amount,
        ]);

        // LOG ANTES DEL REDIRECT
        Log::info('REDIRIGIENDO A PAGO', [
            'session_id' => $parkingSession->id,
            'amount' => $amount,
            'route' => route('payment.initiate'),
            'url' => url(route('payment.initiate'))
        ]);

        // REDIRECT FINAL
        return redirect()->route('payment.initiate');
    }*/

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return back()->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|in:60,120,180,240,360,480',
            'timezone_offset' => 'required|integer',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors(['car_id' => 'Selección de vehículo inválida.']);
        }

        // Verificar si ya tiene una sesión activa
        $existing = ParkingSession::where('car_id', $validated['car_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return back()->withErrors(['car_id' => 'Ya tienes un estacionamiento activo para esta patente.']);
        }

        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int)$validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle no pertenece a la zona seleccionada.']);
        }

        $zone = Zone::findOrFail($validated['zone_id']);
        $rate = $zone->rate ?? 100;

        // Construir fecha y hora con zona horaria del cliente
        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', - ($offsetMinutes / 60));
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString));

        $durationInMinutes = (int)$validated['duration'];
        $endDateTime = $startDateTime->copy()->addMinutes($durationInMinutes);
        $amount = ($durationInMinutes / 60) * $rate;

        try {
            DB::transaction(function () use (
                $validated,
                $car,
                $startDateTime,
                $endDateTime,
                $durationInMinutes,
                $rate,
                $amount
            ) {
                ParkingSession::create([
                    'user_id' => auth()->id(),
                    'car_id' => $validated['car_id'],
                    'zone_id' => $validated['zone_id'],
                    'street_id' => $validated['street_id'],
                    'license_plate' => $car->license_plate ?? strtoupper($car->car_plate ?? 'N/A'),
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'duration' => $durationInMinutes,
                    'rate' => $rate,
                    'amount' => $amount,
                    'status' => 'active',
                ]);

                Log::info('Estacionamiento iniciado', [
                    'user_id' => auth()->id(),
                    'car_id' => $validated['car_id'],
                    'duration' => $durationInMinutes,
                    'amount' => $amount
                ]);
            });

            return redirect()->route('parking.create')
                ->with('success', '¡Estacionamiento activado! Contador iniciado.');
        } catch (\Exception $e) {
            Log::error('Error al iniciar estacionamiento', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->withErrors(['error' => 'Error al iniciar el estacionamiento.']);
        }
    }


    public function show()
    {
        $sessions = ParkingSession::where('user_id', auth()->id())
            ->with(['car', 'zone', 'street'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('parking.show', compact('sessions'));
    }

    public function expire($id)
    {
        $session = ParkingSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya finalizada.'
            ], 404);
        }

        $session->update(['status' => 'expired']);
        Log::info('Sesión expirada automáticamente', ['session_id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Sesión expirada correctamente.'
        ]);
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
                'message' => 'Sesión no encontrada o ya finalizada.'
            ], 404);
        }

        try {
            $session->update([
                'status' => 'cancelled',
                'end_time' => now()
            ]);

            Log::info('Estacionamiento finalizado manualmente', ['session_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Estacionamiento finalizado correctamente.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al finalizar estacionamiento', [
                'session_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkActive($carId)
    {
        try {
            $active = ParkingSession::where('car_id', $carId)
                ->where('user_id', auth()->id())
                ->where('status', 'active')
                ->exists();

            return response()->json(['active' => $active]);
        } catch (\Exception $e) {
            Log::error('Error en checkActive', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    public function getStreetsByZone($zoneId)
    {
        $streets = Street::where('zone_id', $zoneId)->get();
        return response()->json($streets);
    }

    public function getZoneRate($zoneId)
    {
        $zone = Zone::findOrFail($zoneId);
        return response()->json(['rate' => $zone->rate ?? 5.0]);
    }
}
