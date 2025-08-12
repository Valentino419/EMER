<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'car_plate',
        'id_user',
    ];

    public function user(){
        return $this->belongsTo(User::class, );
    }

    public function infractions(){
        return $this->hasMany(Infraction::class, 'id_car');
    }

    public function parking_sessions(){
        return $this->hasMany(ParkingSession::class, 'id_car');
    }
}

