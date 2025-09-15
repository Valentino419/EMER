<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSession;
use App\Models\Payment;
use App\Models\Car; // Si necesitas vincular a auto
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function show()
    {
        // Muestra la última sesión completada (usa ParkingSession en lugar de Payment)
        $parking = ParkingSession::where('user_id', auth()->id()) // Asume agregaste user_id a ParkingSession
                                 ->where('payment_status', 'completed')
                                 ->with('payment') // Relación si existe
                                 ->latest()
                                 ->first();

        if (!$parking) {
            return view('parking.show', ['noParking' => true]);
        }

        return view('parking.show', compact('parking'));
    }
  
    /**
     * Display a listing of the resource (para inspectores, post-sesión).
     */
    public function index()
    {
        $carsConSesion = ParkingSession::where('status', true)
            ->with(['car', 'car.user'])
            ->get();

        return view('payment.index', compact('carsConSesion'));
    }

    /**
     * Store a newly created resource (para cobros post-sesión sin Stripe).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_car' => 'required|integer|exists:cars,id',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,mercadopago,transferencia',
        ]);

        try {
            $sesion = ParkingSession::where('id_car', $validated['id_car'])
                ->where('status', true)
                ->with('car')
                ->first();

            if (!$sesion) {
                return redirect()->route('payment.index')->with('error', 'No se encontró una sesión activa.');
            }

            $duracion = Carbon::parse($sesion->start_time)->diffInMinutes(Carbon::now());
            $monto = $duracion * $sesion->rate;

            DB::transaction(function () use ($sesion, $monto, $validated) {
                // Crea Payment final
                $payment = Payment::create([
                    'id_user' => $sesion->car->id_user,
                    'amount' => $monto,
                    'payment_date' => Carbon::now(),
                    'description' => 'Cobro por estacionamiento',
                    'metodo_pago' => $validated['metodo_pago'],
                    'parking_session_id' => $sesion->id, // Vincula
                ]);

                // Finaliza sesión
                $sesion->update([
                    'end_time' => Carbon::now(),
                    'duration' => $duracion,
                    'status' => false,
                    'payment_status' => 'completed',
                    'payment_id' => $payment->id, // O usa Stripe ID si aplica
                ]);
            });

            return redirect()->route('payment.index')->with('success', "Cobro realizado: $monto");
        } catch (\Exception $e) {
            Log::error('Error en store payment: ' . $e->getMessage());
            return redirect()->route('payment.index')->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

    /**
     * Crea sesión pending y PaymentIntent para pago upfront con Stripe.
     */
    public function create(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $request->validate([
            'license_plate' => 'required|string|max:10',
            'hours' => 'required|numeric|min:0.5|max:24',
        ]);

        try {
            $ratePerHour = 5.00; // Ajusta según zona/tarifa
            $amount = $request->hours * $ratePerHour;

            // Busca o crea auto si no existe (opcional, asume usuario tiene cars)
            $car = Car::firstOrCreate(
                ['license_plate' => strtoupper($request->license_plate)],
                ['id_user' => auth()->id(), /* otros campos */]
            );

            // Crea/actualiza ParkingSession pending
            $parking = ParkingSession::updateOrCreate(
                ['id_car' => $car->id, 'status' => true], // Si ya existe activa, actualiza
                [
                    'license_plate' => strtoupper($request->license_plate),
                    'amount' => $amount,
                    'start_time' => now(),
                    'end_time' => now()->addHours($request->hours),
                    'payment_status' => 'pending',
                    'metodo_pago' => 'tarjeta', // Stripe
                ]
            );

            Log::info('Sesión parking creada para pago', ['id' => $parking->id]);

            // Crea PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // En centavos
                'currency' => 'usd', // Cambia a 'mxn', 'ars', etc.
                'metadata' => [
                    'parking_id' => $parking->id,
                    'user_id' => auth()->id(),
                ],
            ]);

            return view('parking.checkout', [ // Vista correcta para formulario de pago
                'clientSecret' => $paymentIntent->client_secret,
                'parking' => $parking, // Variable consistente
            ]);

        } catch (\Exception $e) {
            Log::error('Error en create payment: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al iniciar pago: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirma pago y actualiza sesión.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'parking_id' => 'required|exists:parking_sessions,id',
            'payment_intent' => 'required|string',
        ]);

        try {
            $parking = ParkingSession::where('id', $request->parking_id)
                                     ->where('user_id', auth()->id()) // Seguridad
                                     ->where('payment_status', 'pending')
                                     ->firstOrFail();

            // Verifica con Stripe
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent);
            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Pago no completado.');
            }

            DB::transaction(function () use ($parking, $request) {
                // Actualiza sesión
                $parking->update([
                    'payment_status' => 'completed',
                    'payment_id' => $request->payment_intent,
                    'status' => true, // Activa el estacionamiento
                ]);

                // Crea Payment final
                Payment::create([
                    'id_user' => auth()->id(),
                    'amount' => $parking->amount,
                    'payment_date' => now(),
                    'description' => 'Pago por estacionamiento medido',
                    'metodo_pago' => 'tarjeta',
                    'parking_session_id' => $parking->id,
                ]);
            });

            Log::info('Pago confirmado', ['parking_id' => $parking->id]);

            return redirect()->route('parking.show')->with('success', 'Pago confirmado. Estacionamiento activo hasta ' . $parking->end_time->format('H:i'));

        } catch (\Exception $e) {
            Log::error('Error en confirm: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al confirmar: ' . $e->getMessage()]);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, env('STRIPE_WEBHOOK_SECRET'));

            if ($event->type === 'payment_intent.succeeded') {
                $parkingId = $event->data->object->metadata->parking_id;
                ParkingSession::where('id', $parkingId)->update(['payment_status' => 'completed']);
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response('Error', 400);
        }
    }
}
