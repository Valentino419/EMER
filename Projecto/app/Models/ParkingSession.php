<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSession extends Model
{
    use HasFactory;

    //protected $table = 'Parkingsession';

    protected $fillable = [
        'id_car',
        'id_street',
        'start_time',
        'end_time',
        'rate',
        'duration',
        'status',
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }

    public function streets()
    {
        return $this->belongsTo(Street::class, 'id_street');
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
