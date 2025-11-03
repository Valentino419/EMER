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

        if (!$sessionId || !$amount) {
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
                        'unit_price' => (float)$amount,
                    ]
                ],
                'back_urls' => [
                    'success' => route('payment.success', [], true),
                    'failure' => route('payment.failure', [], true),
                    'pending' => route('payment.pending', [], true)
                ],
               // 'auto_return' => 'approved',
                'external_reference' => (string)$sessionId,
                'notification_url' => env('MERCADOPAGO_NOTIFICATION_URL'),
            ]);

        Log::info('RESPONSE MP', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);

        if ($response->failed()) {
            Log::error('ERROR API MP', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return redirect()->route('parking.create')
                ->with('error', 'Error de Mercado Pago. Intenta más tarde.');
        }

        $preference = $response->json();
        //$initPoint = $preference['init_point'] ?? null;
        $initPoint = $preference['sandbox_init_point'] ?? null;
        if (!$initPoint) {
            Log::error('NO HAY INIT_POINT', $preference);
            return redirect()->route('parking.create')
                ->with('error', 'No se pudo generar el enlace de pago.');
        }

        Log::info('REDIRIGIENDO A MP', ['init_point' => $initPoint]);

        session()->forget(['parking_session_id', 'parking_amount']);

        return redirect($initPoint);
    }

    public function success()
    {
        return redirect()->route('parking.create')
            ->with('success', '¡Pago exitoso! Estacionamiento activado.');
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

        // Aquí podés actualizar el pago cuando MP te avise
        // Ej: buscar por external_reference y cambiar status

        return response()->json(['status' => 'ok']);
    }
}
