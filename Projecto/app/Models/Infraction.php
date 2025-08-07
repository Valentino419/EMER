<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infraction extends Model
{
   use HasFactory;

    public function inspectors(){
        return $this->belongsTo(Inspector::class, 'inspector_id');
    }

    public function cars(){
        return $this->belongsTo(Car::class, 'car_id');
    }
}
