<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cars extends Model
{
    use HasFactory;
    
    public function user(){
        return $this->belongsTo(Users::class, 'id_user');
    }

    public function infractions(){
        return $this->hasMany(Infractions::class, 'id_cars');
    }

    public function parking_sessions(){
        return $this->hasMany(Parking_Session::class, 'id_car');
    }
}

