<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Create PaymentIntent for a ParkingSession.
     */
    public function createIntentForParking($session, $metadata = [])
    {
        return $this->createIntent($session->amount * 100, array_merge([
            'parking_id' => $session->id,
            'user_id' => $session->user_id,
        ], $metadata));
    }

    /**
     * Create PaymentIntent for a Fine.
     */
    public function createIntentForFine($fine, $metadata = [])
    {
        return $this->createIntent($fine->amount * 100, array_merge([
            'fine_id' => $fine->id,
            'user_id' => $fine->user_id,
        ], $metadata));
    }

    /**
     * Generic PaymentIntent creation.
     */
    private function createIntent($amountInCents, $metadata)
    {
        try {
            return PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => env('STRIPE_CURRENCY', 'usd'),
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating PaymentIntent: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Confirm and create Payment record (reusable).
     */
    public function confirmAndRecord($intentId, $model, $description, $metodoPago = 'tarjeta')
    {
        $intent = PaymentIntent::retrieve($intentId);
        if ($intent->status !== 'succeeded') {
            throw new \Exception('Payment not succeeded.');
        }

        \App\Models\Payment::create([
            'user_id' => $model->user_id,
            'amount' => $model->amount,
            'payment_date' => now(),
            'description' => $description,
            'metodo_pago' => $metodoPago,
            'parking_session_id' => $model instanceof \App\Models\ParkingSession ? $model->id : null,
            'fine_id' => $model instanceof \App\Models\Infraction ? $model->id : null, // Add if Fine model exists
        ]);

        return $intent;
    }
}