<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show()
    {
        $session = ParkingSession::where('user_id', auth()->id())
            ->where('payment_status', 'completed')
            ->latest()
            ->first();

        if (! $session) {
            return view('parking.show', ['noSession' => true]);
        }

        return view('parking.show', compact('session'));
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:parking_sessions,id',
            'token' => 'required|string', // Changed from payment_intent to token
            // Add if needed: 'payment_method_id' => 'required|string',
            // 'installments' => 'required|integer',
        ]);

        try {
            $session = ParkingSession::where('id', $validated['session_id'])
                ->where('user_id', auth()->id())
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $paymentService = app(PaymentService::class);
            $payment = $paymentService->confirmAndRecord($validated['token'], $session, 'Pago por estacionamiento medido');

            // Activate session
            $session->update([
                'payment_status' => 'completed',
                'status' => 'active',
                'payment_id' => $payment->id, // Mercado Pago payment ID
            ]);

            Log::info('Payment confirmed', ['session_id' => $session->id]);

            return redirect()->route('parking.show')->with('success', 'Pago confirmado. Estacionamiento activo hasta '.$session->end_time->format('H:i'));

        } catch (\Exception $e) {
            Log::error('Error in confirm: '.$e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Error al confirmar: '.$e->getMessage()]);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $payload = $request->getContent(); // Raw body for verification
            $headers = $request->headers->all();

            // Verify signature if needed (Mercado Pago provides a way; optional for test)
            // $signature = $headers['x-signature'] ?? null;
            // if (!hash_equals($expectedSignature, $signature)) { throw new \Exception('Invalid signature'); }

            $data = json_decode($payload, true);
            if (! $data || ! isset($data['type']) || $data['type'] !== 'payment') {
                return response('Ignored', 200);
            }

            $paymentId = $data['data']['id'];
            $client = new \MercadoPago\Client\Payment\PaymentClient;
            $payment = $client->get($paymentId);

            if ($payment->status === 'approved') {
                $session = \App\Models\ParkingSession::where('payment_id', $payment->id)->first();
                if ($session && $session->payment_status === 'pending') {
                    $session->update([
                        'payment_status' => 'completed',
                        'status' => 'active',
                    ]);
                    \Log::info('Webhook updated session', ['session_id' => $session->id, 'payment_id' => $payment->id]);
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            \Log::error('Mercado Pago webhook error: '.$e->getMessage());

            return response('Error', 400);
        }
    }
}
