<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSession extends Model
{
    use HasFactory;

    public function cars()
    {
        return $this->belongsTo(Cars::class, 'id_cars');
    }

    public function streets()
    {
        return $this->belongsTo(Streets::class, 'id_streets');
    }
}
