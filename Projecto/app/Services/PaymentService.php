<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function confirmAndRecord($paymentId, $model, $description)
    {
        $payment = Payment::updateOrCreate(
            [
                'license_plate' => $model->license_plate ?? $model->car_plate,
                'id_user' => auth()->id(),
            ],
            [
                'amount' => $model->amount,
                'payment_status' => 'completed',
                'payment_id' => $paymentId,
                'metodo_pago' => 'mercadopago',
            ]
        );

        Log::info('Pago registrado en tabla payments', [
            'payment_id' => $paymentId,
            'model' => get_class($model),
            'model_id' => $model->id
        ]);

        return $payment;
    }
}
