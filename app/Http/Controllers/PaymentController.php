<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSession;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function show()
    {
        $session = ParkingSession::where('user_id', auth()->id())
            ->where('payment_status', 'completed')
            ->latest()
            ->first();

        if (!$session) {
            return view('parking.show', ['noSession' => true]);
        }

        return view('parking.show', compact('session'));
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:parking_sessions,id', // Renamed for clarity
            'payment_intent' => 'required|string',
        ]);

        try {
            $session = ParkingSession::where('id', $validated['session_id'])
                ->where('user_id', auth()->id())
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $paymentService = app(PaymentService::class);
            $paymentService->confirmAndRecord($validated['payment_intent'], $session, 'Pago por estacionamiento medido');

            // Activate session
            $session->update([
                'payment_status' => 'completed',
                'status' => 'active',
                'payment_id' => $validated['payment_intent'],
            ]);

            Log::info('Payment confirmed', ['session_id' => $session->id]);

            return redirect()->route('parking.show')->with('success', 'Pago confirmado. Estacionamiento activo hasta ' . $session->end_time->format('H:i'));

        } catch (\Exception $e) {
            Log::error('Error in confirm: ' . $e->getMessage());
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
                $metadata = $event->data->object->metadata;
                $sessionId = $metadata->parking_id ?? null;
                $infractionId = $metadata->infraction_id ?? null;

                if ($sessionId) {
                    $session = ParkingSession::find($sessionId);
                    if ($session) {
                        $paymentService = app(PaymentService::class);
                        $paymentService->confirmAndRecord($event->data->object->id, $session, 'Pago por estacionamiento medido');
                        $session->update(['payment_status' => 'completed', 'status' => 'active']);
                    }
                } elseif ($infractionId) {
                    // Similar for Fine: Load Fine, confirm, update status
                    $infraction = \App\Models\Infraction::find($infractionId);
                    if ($infraction) {
                        $paymentService = app(PaymentService::class);
                        $paymentService->confirmAndRecord($event->data->object->id, $infraction, 'Pago por multa');
                        $infraction->update(['payment_status' => 'completed']);
                    }
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response('Error', 400);
        }
    }
}
