<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSession extends Model
{
    use HasFactory;

    public function cars()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }

    public function streets()
    {
        return $this->belongsTo(Street::class, 'id_street');
    }
}
