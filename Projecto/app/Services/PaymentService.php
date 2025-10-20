<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function createIntentForParking($session)
    {
        // Not needed for Mercado Pago custom flow; return for compatibility
        return (object) ['client_secret' => null];
    }

    public function confirmAndRecord($token, $model, $description, $paymentMethodId, $installments, $payerDetails)
    {
        try {
            $client = new PaymentClient();
            $paymentData = [
                'transaction_amount' => (float) $model->amount,
                'token' => $token,
                'description' => $description,
                'installments' => (int) $installments,
                'payment_method_id' => $paymentMethodId,
                'payer' => [
                    'email' => $payerDetails['email'],
                    'identification' => [
                        'type' => $payerDetails['identification_type'],
                        'number' => $payerDetails['identification_number'],
                    ],
                ],
                'currency_id' => config('services.mercadopago.currency', 'ARS'),
            ];

            $payment = $client->create($paymentData);

            if ($payment->status === 'approved') {
                \App\Models\Payment::create([
                    'mercadopago_id' => $payment->id, // Changed from stripe_id
                    'amount' => $payment->transaction_amount,
                    'currency' => $payment->currency_id,
                    'description' => $description,
                    'metodo_pago' => $model->metodo_pago ?? 'tarjeta',
                    'session_id' => $model->id, // Link to session
                ]);
                return $payment;
            } else {
                throw new \Exception('Payment not approved: ' . $payment->status);
            }
        } catch (MPApiException $e) {
            Log::error('Mercado Pago API error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error confirming payment: ' . $e->getMessage());
            throw $e;
        }
    }
}