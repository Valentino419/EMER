<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Street extends Model
{
    use HasFactory;

    public function zones()
    {
        return $this->belongsTo(Zones::class, 'id_zones');
    }

    public function parking_Sessions()
    {
        return $this->hasMany(Parking_Session::class, 'id_streets');
    }
}