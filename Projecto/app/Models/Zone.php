<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];
    public function streets()
    {
        return $this->hasMany(Street::class, 'zone_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'zone_id');
    }
     public function parkingSession()
    {
        return $this->belongsTo(ParkingSession::class);
    }
    public function getCurrentRate(): ?int
    {
        $now = \Carbon\Carbon::now();
        $currentDay = $now->format('l'); // e.g., 'Monday'
        $currentTime = $now->toTimeString(); // e.g., '14:30:00'

        $schedule = $this->schedules()
            ->whereJsonContains('days_of_week', $currentDay)
            ->where('start_hour', '<=', $currentTime)
            ->where('end_hour', '>=', $currentTime)
            ->first();

        return $schedule ? $schedule->rate : $this->rate;
    }
}

