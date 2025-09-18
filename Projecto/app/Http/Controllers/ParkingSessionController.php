<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Zone;
use App\Models\Street;
use App\Models\ParkingSession;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ParkingSessionController extends Controller
{
    public function create()
    {
        $cars = Car::where('user_id', auth()->id())->get(); // Only user's cars
        $zones = Zone::all();
        $streets = Street::all();

        return view('parking.create', compact('cars', 'zones', 'streets'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'zone_id' => 'required|exists:zones,id',
            'street_id' => 'required|exists:streets,id',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:30|max:1440', // Min 30 min, max 24h
            'timezone_offset' => 'required|integer',
        ]);

        // Ensure car belongs to user
        $car = Car::findOrFail($validated['car_id']);
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors(['car_id' => 'Invalid car selection.']);
        }

        // Verify street in zone
        $street = Street::findOrFail($validated['street_id']);
        if ($street->zone_id !== (int) $validated['zone_id']) {
            return back()->withErrors(['street_id' => 'La calle no pertenece a la zona.']);
        }

        // Get zone rate
        $zone = Zone::findOrFail($validated['zone_id']);
        $rate = $zone->rate ?? 5.00; // Fallback

        // Handle timezone
        $offsetMinutes = $validated['timezone_offset'];
        $tzString = sprintf('%+03d:00', -($offsetMinutes / 60));
        $startDateTime = Carbon::createFromFormat('H:i', $validated['start_time'], $tzString)
            ->setDateFrom(Carbon::now($tzString));

        // Calculate amount (duration in minutes, rate per hour)
        $amount = ($validated['duration'] / 60) * $rate;

        try {
            DB::transaction(function () use ($validated, $startDateTime, $rate, $amount, $car) {
                // Create pending session
                $session = ParkingSession::create([
                    'user_id' => auth()->id(),
                    'car_id' => $validated['car_id'],
                    'zone_id' => $validated['zone_id'],
                    'street_id' => $validated['street_id'],
                    'license_plate' => $car->license_plate ?? strtoupper($car->car_plate), // Consistent
                    'start_time' => $startDateTime,
                    
                    'duration' => $validated['duration'], // In minutes
                    'rate' => $rate,
                    'amount' => $amount,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                   // 'metodo_pago' => 'tarjeta',
                ]);

                Log::info('Pending parking session created', ['id' => $session->id]);

                // Create PaymentIntent using service
                $paymentService = app(PaymentService::class);
                $paymentIntent = $paymentService->createIntentForParking($session);

                // Redirect to checkout view
                return view('parking.checkout', [
                    'clientSecret' => $paymentIntent->client_secret,
                    'session' => $session,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Error in store: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al iniciar: ' . $e->getMessage()]);
        }
    }
}
