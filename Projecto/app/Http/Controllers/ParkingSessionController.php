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
    // READ - Listar sesiones (now returns back with data)
    public function index(Request $request)
    {
        $search = $request->query('search');

        $sessions = ParkingSession::where('user_id', auth()->id())
            ->with(['car', 'zone', 'street'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('license_plate', 'like', "%{$search}%")
                        ->orWhereHas('zone', function ($z) use ($search) {
                            $z->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('street', function ($s) use ($search) {
                            $s->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Instead of returning view, return back with data (for Inertia/AJAX or Livewire)
        return back()->with([
            'sessions' => $sessions,
            'search' => $search,
        ]);
    }

    // CREATE - Formulario para crear (now returns back with data)
    public function create()
    {
        $cars = Car::where('user_id', auth()->id())->get();
        $zones = Zone::all();
        $streets = Street::all();

        // Expirar sesiones vencidas
        ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereRaw('end_time <= ?', [now()])
            ->update(['status' => 'expired']);

        $activeSessions = ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['car', 'zone', 'street'])
            ->get();

        return view('parking.create')->with([
            'cars' => $cars,
            'zones' => $zones,
            'streets' => $streets,
            'activeSessions' => $activeSessions,
        ]);
    }

    // STORE - Guardar nuevo estacionamiento
    public function store(Request $request)
    {
        Log::info('Entrando a store', ['request' => $request->all()]);

        if (! auth()->check()) {
            return back()->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|in:60,120,180,240,360,480',
            'timezone_offset' => 'required|integer',
            'mercadopago_enabled' => 'nullable|boolean',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors(['car_id' => 'Vehículo no autorizado.']);
        }

        if (ParkingSession::where('car_id', $validated['car_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->exists()
        ) {
            return back()->withErrors(['car_id' => 'Ya tienes un estacionamiento activo para esta patente.']);
        }

        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int) $validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle no pertenece a la zona seleccionada.']);
        }

        $zone = Zone::findOrFail($validated['zone_id']);
        $rate = $zone->rate ?? 5.0;

        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', -($offsetMinutes / 60));
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString));
        $durationInMinutes = (int) $validated['duration'];
        $endDateTime = $startDateTime->copy()->addMinutes($durationInMinutes); // Fixed: was `eingMinutes`
        $amount = ($durationInMinutes / 60) * $rate;

        $mercadopagoEnabled = false;

        if (! $mercadopagoEnabled) {
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
                'status' => 'active',
                'payment_status' => 'completed',
                'metodo_pago' => 'none',
                'license_plate' => $car->license_plate ?? strtoupper($car->car_plate ?? 'N/A'),
            ]);

            Log::info('Sesión activada SIN pago', ['session_id' => $parkingSession->id]);

            return back()->with('success', '¡Estacionamiento activado sin pago! (modo prueba)');
        }

        // MODO CON PAGO
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

        session([
            'parking_session_id' => $parkingSession->id,
            'parking_amount' => $amount,
        ]);

        // Still redirect to payment (this is intentional)
        return redirect()->route('payment.initiate');
    }

    public function show(Request $request)
    {
        $search = $request->query('search');

        $sessions = ParkingSession::where('user_id', auth()->id())
            ->with(['car', 'zone', 'street'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('license_plate', 'like', "%{$search}%")
                        ->orWhereHas('zone', function ($z) use ($search) {
                            $z->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('street', function ($s) use ($search) {
                            $s->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('parking.show', compact('sessions'));
    }

   

    // EDIT - Formulario para editar
    public function edit(ParkingSession $parkingSession)
    {

        if (! in_array($parkingSession->status, ['pending', 'active'])) {
            return back()->withErrors('Solo se pueden editar sesiones pendientes o activas.');
        }

        if ($parkingSession->status === 'active' && $parkingSession->end_time->lt(now()->addMinutes(10))) {
            return back()->withErrors('No se puede editar un estacionamiento que termina en menos de 10 minutos.');
        }

        $cars = Car::where('user_id', auth()->id())->get();
        $zones = Zone::all();
        $streets = Street::where('zone_id', $parkingSession->zone_id)->get();

        return back()->with([
            'parkingSession' => $parkingSession,
            'cars' => $cars,
            'zones' => $zones,
            'streets' => $streets,
        ]);
    }

    // UPDATE - Actualizar sesión
    public function update(Request $request, ParkingSession $parkingSession)
    {
        // Validación (solo estructura)
        $validated = $request->validate([
            'car_id' => 'sometimes|required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|in:60,120,180,240,360,480',
            'status' => 'required|in:pending,active,expired,cancelled',
            'timezone_offset' => 'sometimes|required_with:start_time|integer',
        ]);

        // Validar zona/calle
        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int) $validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle no pertenece a la zona seleccionada.']);
        }

        $zone = Zone::findOrFail($validated['zone_id']);
        $rate = $zone->rate ?? 5.0;

        // Construir start_time con fecha + hora + timezone
        $offsetMinutes = $request->input('timezone_offset', 0);
        $tzString = sprintf('%+03d:00', -$offsetMinutes / 60);

        $startDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['start_date'].' '.$validated['start_time'],
            $tzString
        );

        // Calcular end_time y monto
        $durationMinutes = (int) $validated['duration'];
        $endDateTime = $startDateTime->copy()->addMinutes($durationMinutes);
        $amount = ($durationMinutes / 60) * $rate;

        // Actualización completa (admin)
        DB::transaction(function () use (
            $parkingSession, $validated, $startDateTime, $endDateTime,
            $durationMinutes, $amount, $rate
        ) {
            $data = [
                'zone_id' => $validated['zone_id'],
                'street_id' => $validated['street_id'],
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'duration' => $durationMinutes,
                'amount' => $amount,
                'rate' => $rate,
                'status' => $validated['status'],
            ];

            if (isset($validated['car_id'])) {
                $car = Car::findOrFail($validated['car_id']);
                $data['car_id'] = $validated['car_id'];
                $data['license_plate'] = $car->license_plate
                    ?? strtoupper($car->car_plate ?? 'N/A');
            }

            $parkingSession->update($data);
        });

        return back()->with('success', 'Sesión actualizada correctamente por el administrador.');
    }

    // DELETE - Eliminar sesión
    public function destroy(ParkingSession $parkingSession)
    {

        if ($parkingSession->status === 'active') {
            return back()->withErrors('No puedes eliminar un estacionamiento activo. Finalízalo primero.');
        }

        $parkingSession->delete();

        return back()->with('success', 'Sesión eliminada correctamente.');
    }

    // Métodos adicionales (extend, end, expire, etc.)
    // ... (los que ya tenías, están perfectos)

    public function completePayment(Request $request)
    {
        try {
            $sessionId = session('parking_session_id');
            if (! $sessionId) {
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
                    'user_id' => auth()->id(),
                ]);
            });

            // Limpiar sesión
            session()->forget(['parking_session_id', 'parking_amount']);

            return redirect()->route('parking.create')
                ->with('success', '¡Estacionamiento activado! Contador iniciado.');
        } catch (\Exception $e) {
            Log::error('Error al activar estacionamiento después del pago', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('parking.create')
                ->withErrors(['error' => 'Error al activar el estacionamiento: '.$e->getMessage()]);
        }
    }

    public function extend(Request $request, ParkingSession $session)
    {
        if ($session->user_id !== auth()->id() || $session->start_time->addMinutes($session->duration) <= now()) {
            return response()->json(['success' => false, 'message' => 'No permitido'], 400);
        }
    }

    public function expire($id)
    {
        $session = ParkingSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if (! $session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya finalizada.',
            ], 404);
        }

        $session->update(['status' => 'expired']);
        Log::info('Sesión expirada automáticamente', ['session_id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Sesión expirada correctamente.',
        ]);
    }

    public function end(Request $request, $id)
    {
        $session = ParkingSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if (! $session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya finalizada.',
            ], 404);
        }

        try {
            $session->update([
                'status' => 'cancelled',
                'end_time' => now(), // Actualiza el fin real
            ]);

            Log::info('Estacionamiento finalizado manualmente', ['session_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Estacionamiento finalizado correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al finalizar estacionamiento', [
                'session_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar: '.$e->getMessage(),
            ], 500);
        }
    }

    // app/Http/Controllers/ParkingSessionController.php

    public function checkActive($carId)
    {
        try {
            $active = ParkingSession::where('car_id', $carId)
                ->where('user_id', auth()->id())
                ->where('status', 'active')
                ->exists();

            return response()->json(['active' => $active]);
        } catch (\Exception $e) {
            \Log::error('Error en checkActive', ['error' => $e->getMessage()]);

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
