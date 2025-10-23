
<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ParkingSession;
use App\Models\Payment;
use App\Models\Street;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

class ParkingSessionController extends Controller
{
    public function create()
    {
        $cars = Car::where('user_id', auth()->id())->get();
        $zones = Zone::all();
        $streets = Street::all();
        $activeSessions = ParkingSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where('payment_status', 'completed')
            ->with(['car', 'zone', 'street', 'payment'])
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
            'duration' => 'required|integer|in:60,120,180,240,360,480',
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
        $rate = $zone->rate ?? 5.0; // Usa el campo rate de la zona

        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', - ($offsetMinutes / 60));
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString));
        $endDateTime = $startDateTime->copy()->addMinutes($validated['duration']);

        $amount = ($validated['duration'] / 60) * $rate;

        try {
            $sessionId = DB::transaction(function () use ($validated, $startDateTime, $endDateTime, $rate, $amount, $car) {
                $session = ParkingSession::create([
                    'user_id' => auth()->id(),
                    'car_id' => $validated['car_id'],
                    'zone_id' => $validated['zone_id'],
                    'street_id' => $validated['street_id'],
                    'license_plate' => $car->license_plate ?? strtoupper($car->car_plate),
                    'start_time' => $startDateTime,
                    'end_time' => $endDateTime,
                    'duration' => $validated['duration'],
                    'rate' => $rate,
                    'amount' => $amount,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'metodo_pago' => 'tarjeta',
                ]);

                $payment = Payment::create([
                    'license_plate' => $session->license_plate,
                    'amount' => $amount,
                    'payment_status' => 'pending',
                    'metodo_pago' => 'tarjeta',
                    'id_user' => auth()->id(),
                ]);

                Log::info('Sesión y pago pendientes creados', ['session_id' => $session->id, 'payment_id' => $payment->id]);
                return $session->id;
            });

            // Crear preferencia de pago con Mercado Pago
            $preference = new Preference();
            $item = new Item();
            $item->title = 'Estacionamiento en ' . $street->name . ' (' . $zone->name . ')';
            $item->quantity = 1;
            $item->unit_price = floatval($amount);
            $item->description = $validated['duration'] . ' minutos para patente ' . $car->license_plate;
            $preference->items = [$item];
            $preference->back_urls = [
                'success' => route('parking.payment.success', $sessionId),
                'failure' => route('parking.payment.failure', $sessionId),
                'pending' => route('parking.payment.pending', $sessionId),
            ];
            $preference->external_reference = (string)$sessionId;
            $preference->auto_return = 'approved';
            $preference->save();

            Log::info('Preferencia de pago creada', ['preference_id' => $preference->id, 'init_point' => $preference->init_point]);

            return redirect($preference->init_point);
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            Log::error('Error de API de Mercado Pago: ' . $e->getMessage(), ['status' => $e->getStatusCode()]);
            return back()->withErrors(['error' => 'Error al iniciar pago: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Error general al iniciar pago: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al iniciar pago: ' . $e->getMessage()]);
        }
    }

    public function paymentSuccess($sessionId)
    {
        $session = ParkingSession::findOrFail($sessionId);
        if ($session->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para esta sesión.');
        }

        $paymentId = request('payment_id');
        if ($paymentId) {
            try {
                $payment = \MercadoPago\Payment::find_by_id($paymentId);
                if ($payment && $payment->status === 'approved') {
                    $session->update([
                        'status' => 'active',
                        'payment_status' => 'completed',
                        'payment_id' => $paymentId,
                    ]);

                    Payment::where('license_plate', $session->license_plate)
                        ->where('id_user', auth()->id())
                        ->where('payment_status', 'pending')
                        ->update([
                            'payment_id' => $paymentId,
                            'payment_status' => 'completed',
                        ]);

                    Log::info('Pago aprobado', ['session_id' => $sessionId, 'payment_id' => $paymentId]);
                    return redirect()->route('parking.create')->with('success', 'Pago confirmado. Estacionamiento iniciado.');
                } else {
                    Log::warning('Pago no aprobado', ['payment_id' => $paymentId, 'status' => $payment ? $payment->status : 'no_payment']);
                }
            } catch (\MercadoPago\Exceptions\MPApiException $e) {
                Log::error('Error al verificar pago: ' . $e->getMessage(), ['status' => $e->getStatusCode()]);
            }
        }

        $session->update(['status' => 'cancelled', 'payment_status' => 'failed']);
        Payment::where('license_plate', $session->license_plate)
            ->where('id_user', auth()->id())
            ->where('payment_status', 'pending')
            ->update(['payment_status' => 'failed']);
        return redirect()->route('parking.create')->withErrors(['error' => 'Pago no aprobado. Intenta de nuevo.']);
    }

    public function paymentFailure($sessionId)
    {
        $session = ParkingSession::findOrFail($sessionId);
        $session->update(['status' => 'cancelled', 'payment_status' => 'failed']);
        Payment::where('license_plate', $session->license_plate)
            ->where('id_user', auth()->id())
            ->where('payment_status', 'pending')
            ->update(['payment_status' => 'failed']);
        Log::info('Pago rechazado, sesión cancelada', ['session_id' => $sessionId]);
        return redirect()->route('parking.create')->withErrors(['error' => 'Pago rechazado. Estacionamiento cancelado.']);
    }

    public function paymentPending($sessionId)
    {
        return $this->paymentSuccess($sessionId);
    }

    public function expire($id)
    {
        $session = ParkingSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesión no encontrada o ya finalizada.'], 404);
        }
        $session->expire();
        Log::info('Sesión expirada', ['session_id' => $id]);
        return response()->json(['success' => true, 'message' => 'Sesión expirada correctamente.']);
    }

    public function end(Request $request, $id)
    {
        $session = ParkingSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesión no encontrada o ya finalizada.'], 404);
        }
        $session->update(['status' => 'cancelled']);
        Log::info('Estacionamiento finalizado manualmente', ['session_id' => $id]);
        return response()->json(['success' => true, 'message' => 'Estacionamiento finalizado correctamente.']);
    }

    public function checkActive($carId)
    {
        $session = ParkingSession::where('car_id', $carId)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        return response()->json(['active' => !!$session]);
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
