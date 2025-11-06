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

    public function store(Request $request)
    {
        Log::info('Entrando a store', ['request' => $request->all()]);

        if (!auth()->check()) {
            return back()->withErrors(['error' => 'Debes iniciar sesión.']);
        }
        //dd($request->all());

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|in:60,120,180,240,360,480',
            'timezone_offset' => 'required|integer',
            'mercadopago_enabled' => 'nullable|boolean', // Agregamos el switch como booleano opcional
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

        // Verificamos el switch de Mercado Pago
        $mercadopagoEnabled = $validated['mercadopago_enabled'] ?? false; // Por defecto true si no se envía
        //DD($mercadopagoEnabled);
        if (!$mercadopagoEnabled) {
            //DD('sin');
            // Modo sin pago: Crear sesión directamente como activa
            $parkingSession = ParkingSession::create([
                'car_id' => $validated['car_id'],
                'zone_id' => $validated['zone_id'],
                'street_id' => $validated['street_id'],
                'user_id' => auth()->id(),
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'duration' => $durationInMinutes,
                'rate' => $rate,
                'amount' => $amount,
                'status' => 'active', // Directamente activa
                'payment_status' => 'completed', // O 'paid' si prefieres simular pago, pero 'skipped' para diferenciar
                'metodo_pago' => 'none', // O lo que prefieras
                'license_plate' => $car->license_plate ?? strtoupper($car->car_plate ?? 'N/A'),
            ]);

            Log::info('Sesión activada sin pago (modo skip)', ['session_id' => $parkingSession->id]);

            return redirect()->route('parking.create')
                ->with('success', '¡Estacionamiento activado sin pago! Contador iniciado.');
        } else { 
            //DD('else');
            // Modo normal con pago
            $parkingSession = ParkingSession::create([
                'car_id' => $validated['car_id'],
                'zone_id' => $validated['zone_id'],
                'street_id' => $validated['street_id'],
                'user_id' => auth()->id(),
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'duration' => $durationInMinutes,
                'rate' => $rate,
                'amount' => $amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'metodo_pago' => 'tarjeta',
                'license_plate' => $car->license_plate ?? strtoupper($car->car_plate ?? 'N/A'),
            ]);

            Log::info('Sesión pendiente creada', ['session_id' => $parkingSession->id]);

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

            // REDIRECT FINAL A PAGOS
            return redirect()->route('payment.initiate');
        }
    }

    public function completePayment(Request $request)
    {
        try {
            $sessionId = session('parking_session_id');
            if (!$sessionId) {
                throw new \Exception('No se encontró sesión de estacionamiento pendiente.');
            }

            $parkingSession = ParkingSession::findOrFail($sessionId);

            if ($parkingSession->user_id !== auth()->id() || $parkingSession->status !== 'pending') {
                throw new \Exception('Sesión inválida o ya procesada.');
            }

            // Aquí puedes agregar verificación adicional del pago si es necesario
            // (por ejemplo, confirmar con el proveedor de pagos via $request)

            DB::transaction(function () use ($parkingSession) {
                $parkingSession->update([
                    'status' => 'active',
                    'payment_status' => 'paid',
                ]);

                Log::info('Estacionamiento activado después del pago', [
                    'session_id' => $parkingSession->id,
                    'user_id' => auth()->id()
                ]);
            });

            // Limpiar sesión
            session()->forget(['parking_session_id', 'parking_amount']);

            return redirect()->route('parking.create')
                ->with('success', '¡Estacionamiento activado! Contador iniciado.');
        } catch (\Exception $e) {
            Log::error('Error al activar estacionamiento después del pago', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('parking.create')
                ->withErrors(['error' => 'Error al activar el estacionamiento: ' . $e->getMessage()]);
        }
    }

    public function extend(Request $request, ParkingSession $session)
    {
        if ($session->user_id !== auth()->id() || $session->start_time->addMinutes($session->duration) <= now()) {
            return response()->json(['success' => false, 'message' => 'No permitido'], 400);
        }

        $extra = $request->extra_minutes;
        $rate = $session->zone->rate ?? 5.0;
        $extraAmount = ($extra / 60) * $rate;

        $session->duration += $extra;
        $session->amount += $extraAmount;
        $session->save();

        $newEnd = $session->start_time->copy()->addMinutes($session->duration);

        return response()->json([
            'success' => true,
            'new_end_time' => $newEnd->timestamp * 1000,
            'extra_amount' => $extraAmount,
            'total_amount' => $session->amount,
            'new_duration' => $session->duration,
        ]);
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