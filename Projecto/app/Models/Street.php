<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'id_zone');
    }
    public function parkingsessions()
    {
        return $this->hasMany(ParkingSession::class, 'id_street');
    }
}
