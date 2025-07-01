<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;
    
    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function infractions(){
        return $this->hasMany(Infraction::class, 'id_car');
    }

    public function parking_sessions(){
        return $this->hasMany(Parking_Session::class, 'id_car');
    }
}

