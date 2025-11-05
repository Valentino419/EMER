<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function initiate()
    {
        Log::info('INICIANDO PAGO MP', [
            'session_id' => session('parking_session_id'),
            'amount' => session('parking_amount'),
        ]);

        $sessionId = session('parking_session_id');
        $amount = session('parking_amount');

        if (! $sessionId || ! $amount) {
            Log::error('FALTAN DATOS DE SESIÓN');

            return redirect()->route('parking.create')
                ->with('error', 'No se pudo iniciar el pago. Intenta de nuevo.');
        }

        $response = Http::withToken(env('MERCADOPAGO_ACCESS_TOKEN'))
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => [
                    [
                        'title' => 'Estacionamiento EMER',
                        'quantity' => 1,
                        'currency_id' => 'ARS',
                        'unit_price' => (float) $amount,
                    ],
                ], 'payer' => [
                    'email' => auth()->user()->email ?? 'test@emer.com', // <-- AQUÍ ESTÁ EL FIX
                    'name' => auth()->user()->name ?? 'Cliente',
                    'surname' => auth()->user()->surname ?? 'EMER',
                ],
                'back_urls' => [
                    'success' => route('payment.success', [], true),
                    'failure' => route('payment.failure', [], true),
                    'pending' => route('payment.pending', [], true),
                ],
                // 'auto_return' => 'approved',
                'external_reference' => (string) $sessionId,
                'notification_url' => env('MERCADOPAGO_NOTIFICATION_URL'),
            ]);

        Log::info('RESPONSE MP', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        if ($response->failed()) {
            Log::error('ERROR API MP', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect()->route('parking.create')
                ->with('error', 'Error de Mercado Pago. Intenta más tarde.');
        }

        $preference = $response->json();
        // $initPoint = $preference['init_point'] ?? null;
        $initPoint = $preference['init_point'] ?? null;
        if (! $initPoint) {
            Log::error('NO HAY INIT_POINT', $preference);

            return redirect()->route('parking.create')
                ->with('error', 'No se pudo generar el enlace de pago.');
        }

        Log::info('REDIRIGIENDO A MP', ['init_point' => $initPoint]);

        session()->forget(['parking_session_id', 'parking_amount']);

        return redirect($initPoint);
    }

    public function success(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $externalReference = $request->query('external_reference'); // Tu $sessionId

        if (! $paymentId || ! $externalReference) {
            Log::error('FALTAN PARAMS EN SUCCESS', $request->all());

            return redirect()->route('parking.create')->with('error', 'Error en verificación de pago.');
        }

        // Verifica el pago con API
        $response = Http::withToken(env('MERCADOPAGO_ACCESS_TOKEN'))
            ->get("https://api.mercadopago.com/v1/payments/$paymentId");

        Log::info('VERIFICACION PAGO', ['status' => $response->status(), 'json' => $response->json()]);

        if ($response->successful()) {
            $payment = $response->json();
            if ($payment['status'] === 'approved' && $payment['external_reference'] == $externalReference) {
                // Llama a completePayment o actualiza aquí la DB
                $parkingSession = ParkingSession::find($externalReference);
                if ($parkingSession && $parkingSession->status === 'pending') {
                    $parkingSession->update([
                        'status' => 'active',
                        'payment_status' => 'paid',
                    ]);
                    Log::info('Pago verificado y sesión activada', ['session_id' => $externalReference]);

                    return redirect()->route('parking.create')->with('success', '¡Pago exitoso! Estacionamiento activado.');
                }
            }
        }

        return redirect()->route('parking.create')->with('error', 'Pago no verificado o inválido.');
    }

    public function failure()
    {
        return redirect()->route('parking.create')
            ->with('error', 'El pago fue rechazado.');
    }

    public function pending()
    {
        return redirect()->route('parking.create')
            ->with('success', 'Pago pendiente. Te avisaremos cuando se acredite.');
    }

    public function webhook(Request $request)
    {
        Log::info('WEBHOOK MP RECIBIDO', $request->all());

        $type = $request->input('type');
        $dataId = $request->input('data.id');

        if ($type === 'payment' && $dataId) {
            $response = Http::withToken(env('MERCADOPAGO_ACCESS_TOKEN'))
                ->get("https://api.mercadopago.com/v1/payments/$dataId");

            if ($response->successful()) {
                $payment = $response->json();
                if ($payment['status'] === 'approved') {
                    $sessionId = $payment['external_reference'];
                    $parkingSession = ParkingSession::find($sessionId);
                    if ($parkingSession && $parkingSession->status === 'pending') {
                        $parkingSession->update([
                            'status' => 'active',
                            'payment_status' => 'paid',
                        ]);
                        Log::info('Pago confirmado via webhook', ['session_id' => $sessionId]);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
