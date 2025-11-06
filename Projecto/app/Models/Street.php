<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $fillable = ['name', 'start_street', 'end_street', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
    public function parkingsessions()
    {
        return $this->hasMany(ParkingSession::class, 'id_street');
    }
}
