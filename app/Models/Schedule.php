<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';
   

  

    protected $fillable = [
        'zone_id',
        'days_of_week',
        'start_hour',
        'end_hour',
        'rate',
    ];

    protected $casts = [
        'days_of_week' => 'array', // JSON array for days_of_week
        'start_hour' => 'string',  // Ensure time is treated as string (H:i:s)
        'end_hour' => 'string',    // Ensure time is treated as string (H:i:s)
        'rate' => 'decimal:2',     // Treat rate as decimal
        'days_of_week' => 'array', // Automatically cast JSON to array
    ];
    public function zone(){
        return $this->belongsTo(Zone::class, 'zone_id');
    }
    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = $value ?? 10.00; // Fallback to 12.00 if null
    }
}
