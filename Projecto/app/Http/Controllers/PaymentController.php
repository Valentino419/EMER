<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSession;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listar sesiones de estacionamiento activas con sus autos y usuarios
        $carsConSesion = ParkingSession::where('status', true)
            ->with(['car', 'car.user']) // Cargar relaciones
            ->get();

        return view('payment.index', compact('carsConSesion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_car' => 'required|integer|exists:cars,id',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,mercadopago,transferencia',
        ]);

        try {
            // Buscar sesión activa del auto
            $sesion = ParkingSession::where('id_car', $validated['id_car'])
                ->where('status', true)
                ->with('car')
                ->first();

            if (!$sesion) {
                return redirect()->route('payment.index')->with('error', 'No se encontró una sesión activa para este vehículo.');
            }

            // Calcular duración en minutos
            $duracion = Carbon::parse($sesion->start_time)->diffInMinutes(Carbon::now());

            // Calcular monto (rate es tarifa por minuto)
            $monto = $duracion * $sesion->rate;

            DB::transaction(function () use ($sesion, $monto, $validated) {
                // Registrar pago
                Payment::create([
                    'id_user' => $sesion->car->id_user,
                    'amount' => $monto,
                    'payment_date' => Carbon::now(),
                    'description' => 'Cobro por estacionamiento',
                    'metodo_pago' => $validated['metodo_pago'],
                ]);

                // Finalizar sesión
                $sesion->update([
                    'end_time' => Carbon::now(),
                    'duration' => $duracion,
                    'status' => false,
                ]);
            });

            return redirect()->route('payment.index')->with('success', "Cobro realizado: $monto");
        } catch (\Exception $e) {
            return redirect()->route('payment.index')->with('error', 'Error al procesar el cobro: ' . $e->getMessage());
        }
    }
}
