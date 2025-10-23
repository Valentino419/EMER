```php
<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\SDK;
use MercadoPago\Payment as MercadoPagoPayment;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        SDK =

::setAccessToken(env('MP_ACCESS_TOKEN'));
    }

    /**
     * Confirmar pago desde Mercado Pago (Checkout Pro)
     */
    public function confirm(Request $request, $sessionId)
    {
        $session = ParkingSession::where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->firstOrFail();

        $paymentId = $request->query('payment_id') ?? $request->query('data.id');

        if (!$paymentId) {
            return redirect()->route('parking.create')
                ->withErrors(['error' => 'No se recibió ID de pago.']);
        }

        try {
            $mpPayment = MercadoPagoPayment::find_by_id($paymentId);

            if ($mpPayment->status === 'approved') {
                // Registrar en tabla payments
                $this->paymentService->confirmAndRecord(
                    $paymentId,
                    $session,
                    'Pago por estacionamiento medido (Mercado Pago)'
                );

                // Activar sesión
                $session->update([
                    'payment_status' => 'completed',
                    'status' => 'active',
                    'payment_id' => $paymentId,
                ]);

                Log::info('Pago Mercado Pago aprobado', [
                    'session_id' => $session->id,
                    'mp_payment_id' => $paymentId
                ]);

                return redirect()->route('parking.create')
                    ->with('success', 'Pago confirmado. Estacionamiento activo hasta ' . $session->end_time->format('H:i'));
            }

            // Si no está aprobado
            $session->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return redirect()->route('parking.create')
                ->withErrors(['error' => 'Pago no aprobado: ' . ucfirst($mpPayment->status)]);

        } catch (\Exception $e) {
            Log::error('Error al verificar pago MP: ' . $e->getMessage());
            return redirect()->route('parking.create')
                ->withErrors(['error' => 'Error al verificar pago.']);
        }
    }

    /**
     * Webhook de Mercado Pago (opcional, para mayor seguridad)
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        if (!isset($payload['data']['id'])) {
            return response('Invalid payload', 400);
        }

        $paymentId = $payload['data']['id'];

        try {
            $mpPayment = MercadoPagoPayment::find_by_id($paymentId);

            if ($mpPayment->status === 'approved' && $mpPayment->external_reference) {
                $sessionId = $mpPayment->external_reference;
                $session = ParkingSession::find($sessionId);

                if ($session && $session->payment_status === 'pending') {
                    $this->paymentService->confirmAndRecord(
                        $paymentId,
                        $session,
                        'Pago por estacionamiento medido (Webhook MP)'
                    );

                    $session->update([
                        'payment_status' => 'completed',
                        'status' => 'active',
                        'payment_id' => $paymentId,
                    ]);

                    Log::info('Pago confirmado vía webhook MP', ['session_id' => $sessionId]);
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Webhook MP error: ' . $e->getMessage());
            return response('Error', 400);
        }
    }

    /**
     * Mostrar recibo (opcional)
     */
    public function show($sessionId)
    {
        $session = ParkingSession::where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->where('payment_status', 'completed')
            ->with(['car', 'zone', 'street'])
            ->firstOrFail();

        return view('parking.receipt', compact('session'));
    }
}
```