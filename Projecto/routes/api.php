<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Zone;
use App\Models\Street;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PaymentController;

Route::post('/webhook/mercadopago', [PaymentController::class, 'webhook']);

Route::get('/zones/{zone}/streets', function (Zone $zone) {
    return Street::where('zone_id', $zone->id)->get(['id', 'name', 'zone_id','rate']);
});


/*Route::get('/zones/{zone}/rate', function (Zone $zone, Request $request) {
    // Get current day and time in the app's timezone
    $currentDay = Carbon::now()->format('l'); // e.g., "Monday"
    $currentTime = Carbon::now()->format('H:i:s'); // e.g., "19:47:00"

    // Log request details for debugging
    Log::info('API /zones/{zone}/rate called', [
        'zone_id' => $zone->id,
        'current_day' => $currentDay,
        'current_time' => $currentTime,
    ]);

    // Find matching schedule
    $schedule = Schedule::where('zone_id', $zone->id)
        ->whereJsonContains('days_of_week', $currentDay)
        ->where('start_hour', '<=', $currentTime)
        ->where('end_hour', '>=', $currentTime)
        ->first();

    // Log schedule result
    if (!$schedule) {
        Log::warning('No schedule found for zone', [
            'zone_id' => $zone->id,
            'current_day' => $currentDay,
            'current_time' => $currentTime,
        ]);
    } else {
        Log::info('Schedule found', [
            'schedule_id' => $schedule->id,
            'days_of_week' => $schedule->days_of_week,
            'start_hour' => $schedule->start_hour,
            'end_hour' => $schedule->end_hour,
            'rate' => $schedule->rate,
        ]);
    }

    // Return rate or fallback
    return response()->json(['rate' => $schedule ? $schedule->rate : 12]);
});*/


