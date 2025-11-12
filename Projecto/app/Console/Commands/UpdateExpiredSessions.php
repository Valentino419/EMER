<?php

namespace App\Console\Commands;

use App\Models\ParkingSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-expired-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ParkingSession::where('status', 'active')
            ->where('end_time', '<=', Carbon::now('UTC'))
            ->update(['status' => 'expired']);
        $this->info('Updated expired sessions.');

        // Optional: Log the number of updates for monitoring
      
    }
}
