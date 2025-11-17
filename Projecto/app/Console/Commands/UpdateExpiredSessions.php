<?php

namespace App\Console\Commands;

use App\Models\ParkingSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateExpiredSessions extends Command
{
    /** @var string */
    protected $signature = 'parking:update-expired';

    /** @var string */
    protected $description = 'Mark active parking sessions as expired when their end_time has passed';

    public function handle()
    {
        // Use LOCAL time (same as your system and DB)
        $now = Carbon::now(); // Remove 'UTC' → uses config/app.php timezone

        $this->info('Current local now: '.$now->toDateTimeString());

        $updated = ParkingSession::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'expired']);

        $this->info("Updated {$updated} expired parking session(s).");

        return self::SUCCESS;
    }
}
