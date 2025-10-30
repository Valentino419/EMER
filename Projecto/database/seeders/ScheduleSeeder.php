<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        Schedule::create([
            'zone_id' => 1,
            'day' => 2, // Tuesday
            'start_time' => '08:00:00',
            'end_time' => '20:00:00',
            'rate' => 100.00, // Adjust as needed
        ]);
        Schedule::create([
            'zone_id' => 1,
            'day' => 4, // Thursday
            'start_time' => '08:00:00',
            'end_time' => '20:00:00',
            'rate' => 100.00,
        ]);
    }
}
