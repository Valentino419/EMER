<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class PaymentService
{
    public function __construct()
    {
        // Set Mercado Pago access token
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    // This method is no longer needed for Mercado Pago (token is created client-side).
    // If you need to pre-create something, you could generate a preference here, but for custom flow, skip.
    public function createIntentForParking($session)
    {
        // Placeholder or remove; return null or an empty object if needed for compatibility
        return (object) ['client_secret' => null]; // Adjust controller to not use client_secret
    }

    public function confirmAndRecord($token, $model, $description)
    {
        try {
            $client = new PaymentClient();
            $paymentData = [
                'transaction_amount' => (float) $model->amount,
                'token' => $token,
                'description' => $description,
                'installments' => 1, // Default; can make dynamic
                'payment_method_id' => 'visa', // Dynamic from form; adjust based on card detection
                'payer' => [
                    'email' => auth()->user()->email,
                    'identification' => [
                        'type' => 'DNI', // From form
                        'number' => '12345678', // From form
                    ],
                ],
            ];

            $payment = $client->create($paymentData);

            if ($payment->status === 'approved') {
                \App\Models\Payment::create([
                    'mercadopago_id' => $payment->id, // Use mercadopago_id instead of stripe_id
                    'amount' => $payment->transaction_amount,
                    'currency' => $payment->currency_id,
                    'description' => $description,
                    'metodo_pago' => $model->metodo_pago ?? 'tarjeta',
                    // Add session_id: 'session_id' => $model->id if polymorphic
                ]);
                return $payment;
            } else {
                throw new \Exception('Payment not approved: ' . $payment->status);
            }
        } catch (MPApiException $e) {
            \Log::error('Mercado Pago API error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error confirming payment: ' . $e->getMessage());
            throw $e;
        }
    }
}