<?php

namespace App\Console\Commands;

use App\Models\ParkingSession;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parking:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark parking sessions whose end_time has passed as expired';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now('UTC');

        // -----------------------------------------------------------------
        // 1. Bulk update – fastest & safest for production
        // -----------------------------------------------------------------
        $updated = ParkingSession::query()
            ->where('status', 'active')
            ->whereNotNull('end_time')
            ->where('end_time', '<=', $now)
            ->where('end_time', '>=', $now->copy()->subYears(5))   // avoid junk data
            ->update(['status' => 'expired']);

        $this->info("Updated {$updated} expired parking session(s).");

        // -----------------------------------------------------------------
        // 2. OPTIONAL: per-session logging / notifications
        // -----------------------------------------------------------------
        // If you need to fire events, send emails, push notifications, etc.
        // replace the bulk update with the loop below (uncomment it).

        /*
        $expired = ParkingSession::query()
            ->where('status', 'active')
            ->whereNotNull('end_time')
            ->where('end_time', '<=', $now)
            ->where('end_time', '>=', $now->copy()->subYears(5))
            ->get();

        foreach ($expired as $session) {
            $session->update(['status' => 'expired']);

            // Example: log
            Log::info('Parking session expired', [
                'session_id' => $session->id,
                'license_plate' => $session->license_plate,
                'end_time' => $session->end_time,
            ]);

            // Example: notify the user
            // $session->user->notify(new \App\Notifications\ParkingSessionExpired($session));
        }

        $this->info("Processed {$expired->count()} expired session(s).");
        */

        return self::SUCCESS;
    }
}
