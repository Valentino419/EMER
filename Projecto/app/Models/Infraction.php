<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infraction extends Model
{
   use HasFactory;

    public function inspector(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car(){
        return $this->belongsTo(Car::class, 'car_id');
    }
}
