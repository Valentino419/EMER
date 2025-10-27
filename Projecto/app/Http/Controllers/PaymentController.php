<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function initiate(Request $request)
    {
        Log::info('Llegando a payment.initiate', [
            'request' => $request->all(),
            'session' => session('parking_data'),
            'token' => substr(env('MP_ACCESS_TOKEN'), 0, 10) . '...',
        ]);

        $accessToken = env('MP_ACCESS_TOKEN');
        if (empty($accessToken)) {
            Log::error('Token de acceso no encontrado', ['env' => env('MP_ACCESS_TOKEN')]);
            return redirect()->route('parking.create')->withErrors(['error' => 'Token de Mercado Pago no configurado.']);
        }

        // Verificar si el token es de sandbox para pruebas locales
        if (strpos($accessToken, 'TEST-') !== 0) {
            Log::warning('Token no es de sandbox', ['token' => substr($accessToken, 0, 10) . '...']);
            return redirect()->route('parking.create')->withErrors(['error' => 'Usa un token de pruebas (TEST-...) para pruebas locales.']);
        }

        // Inicializar el SDK
        MercadoPagoConfig::setAccessToken($accessToken);

        $data = $request->all();
        if (empty($data) || empty($data['license_plate']) || empty($data['amount'])) {
            Log::error('Datos de estacionamiento no encontrados', ['request' => $data]);
            return redirect()->route('parking.create')->withErrors(['error' => 'Faltan datos para el pago.']);
        }

        // Validar monto
        if (floatval($data['amount']) <= 0) {
            Log::error('Monto inválido', ['amount' => $data['amount']]);
            return redirect()->route('parking.create')->withErrors(['error' => 'El monto debe ser mayor a 0.']);
        }

        // Validar placa
        if (strlen($data['license_plate']) > 200) {
            Log::error('Placa demasiado larga', ['license_plate' => $data['license_plate']]);
            return redirect()->route('parking.create')->withErrors(['error' => 'La placa es demasiado larga.']);
        }

        try {
            $client = new PreferenceClient();

            // Crear item
            $item = new Item();
            $item->title = "Estacionamiento - {$data['license_plate']}";
            $item->quantity = 1;
            $item->unit_price = floatval($data['amount']);
            $item->currency_id = 'ARS';

            // External reference único
            $externalReference = 'parking_' . auth()->id() . '_' . time();

            // Crear preferencia
            $preference = $client->create([
                'items' => [$item],
                'back_urls' => [
                    'success' => 'https://www.test.com/payment/success', // Ficticia para pruebas
                    'failure' => 'https://www.test.com/payment/failure',
                    'pending' => 'https://www.test.com/payment/pending',
                ],
                'external_reference' => $externalReference,
                // Sin auto_return para evitar errores
            ]);

            Log::info('Preferencia de Mercado Pago creada', [
                'init_point' => $preference->init_point,
                'external_reference' => $externalReference,
            ]);

            // Guardar en sesión para pruebas manuales
            session(['last_init_point' => $preference->init_point, 'last_external_reference' => $externalReference]);

            return redirect($preference->init_point);
        } catch (\Exception $e) {
            Log::error('Error al crear preferencia de Mercado Pago', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('parking.create')->withErrors(['error' => 'Error al procesar el pago: ' . $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        Log::info('Pago exitoso', ['request' => $request->all()]);
        return redirect()->route('parking.create')->with('success', 'Pago exitoso');
    }

    public function failure(Request $request)
    {
        Log::info('Pago fallido', ['request' => $request->all()]);
        return redirect()->route('parking.create')->withErrors(['error' => 'Pago fallido']);
    }

    public function pending(Request $request)
    {
        Log::info('Pago pendiente', ['request' => $request->all()]);
        return redirect()->route('parking.create')->with('info', 'Pago pendiente');
    }
}
