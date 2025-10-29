<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function initiate()
    {
        Log::info('INICIANDO PAGO MP', [
            'session_id' => session('parking_session_id'),
            'amount' => session('parking_amount'),
            'url' => url(''),
            'token' => substr(env('MERCADOPAGO_ACCESS_TOKEN'), 0, 20) . '...'
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
                    'success' => url(route('payment.success')),
                    'failure' => url(route('payment.failure')),
                    'pending' => url(route('payment.pending'))
                ],
                'external_reference' => (string)$sessionId,
                'notification_url' => url('/webhook/mercadopago'),
            ]);

        if ($response->failed()) {
            Log::error('ERROR MERCADO PAGO', [
                'status' => $response->status(),
                'body' => $response->body(),
                'request' => $response->transferStats?->getRequest()?->getBody()->getContents()
            ]);
            return redirect()->route('parking.create')
                ->with('error', 'Error al conectar con Mercado Pago. Intenta más tarde.');
        }

        $preference = $response->json();
        $initPoint = $preference['init_point'] ?? null;

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
            ->with('success', '¡Pago realizado con éxito!');
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
}
