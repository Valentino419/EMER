<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

class PaymentController extends Controller
{
    public function initiate()
    {
        $data = session('parking_data');

        if (!$data) {
            return redirect()->route('parking.create')
                ->withErrors(['error' => 'Datos de estacionamiento perdidos. Intenta de nuevo.']);
        }

        $preference = new Preference();
        $item = new Item();
        $item->title = "Estacionamiento - {$data['license_plate']}";
        $item->quantity = 1;
        $item->unit_price = floatval($data['amount']);
        $item->currency_id = 'ARS';
        $preference->items = [$item];

        $preference->back_urls = [
            'success' => route('payment.success'),
            'failure' => route('payment.failure'),
            'pending' => route('payment.pending'),
        ];
        $preference->auto_return = 'approved';
        $preference->external_reference = 'parking_' . auth()->id() . '_' . time();

        $preference->save();

        // Guardar para verificar después
        session(['preference_id' => $preference->id]);

        Log::info('Preferencia MP creada', [
            'preference_id' => $preference->id,
            'amount' => $data['amount'],
            'user_id' => auth()->id()
        ]);

        return redirect($preference->init_point);
    }

    /**
     * Pago aprobado → crea sesión ACTIVE
     */
    public function success()
    {
        $data = session('parking_data');
        if (!$data) {
            return redirect()->route('parking.create')
                ->withErrors(['error' => 'Datos perdidos. Intenta de nuevo.']);
        }

        $paymentId = request('payment_id');
        if (!$paymentId) {
            return $this->failure();
        }

        try {
            $payment = \MercadoPago\Payment::find_by_id($paymentId);

            if ($payment->status === 'approved') {
                DB::transaction(function () use ($data, $paymentId) {
                    ParkingSession::create([
                        'user_id' => auth()->id(),
                        'car_id' => $data['car_id'],
                        'zone_id' => $data['zone_id'],
                        'street_id' => $data['street_id'],
                        'license_plate' => $data['license_plate'],
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'duration' => $data['duration'],
                        'rate' => $data['rate'],
                        'amount' => $data['amount'],
                        'status' => 'active',
                        'payment_status' => 'completed',
                        'payment_id' => $paymentId,
                    ]);
                });

                // Limpiar datos temporales
                session()->forget(['parking_data', 'preference_id']);

                Log::info('Estacionamiento iniciado tras pago', [
                    'payment_id' => $paymentId,
                    'amount' => $data['amount']
                ]);

                return redirect()->route('parking.create')
                    ->with('success', '¡Pago exitoso! Tu estacionamiento está activo.');
            }
        } catch (\Exception $e) {
            Log::error('Error verificando pago', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
        }

        return $this->failure();
    }

    /**
     * Pago rechazado
     */
    public function failure()
    {
        session()->forget(['parking_data', 'preference_id']);

        return redirect()->route('parking.create')
            ->withErrors(['error' => 'El pago fue rechazado. Intenta nuevamente.']);
    }

    /**
     * Pago pendiente
     */
    public function pending()
    {
        return redirect()->route('parking.create')
            ->with('info', 'Pago pendiente. Te avisaremos cuando se acredite.');
    }

    /**
     * Webhook (opcional, para producción)
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        if ($request->query('topic') === 'payment') {
            $paymentId = $request->query('data.id');
            try {
                $payment = \MercadoPago\Payment::find_by_id($paymentId);
                Log::info('Webhook recibido', ['payment_id' => $paymentId, 'status' => $payment->status]);
            } catch (\Exception $e) {
                Log::error('Error en webhook', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => 'received']);
    }
}
