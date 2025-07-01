<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infraction extends Model
{
   use HasFactory;

    public function inspectors(){
        return $this->belongsTo(Inspector::class, 'id_inspector');
    }

    public function cars(){
        return $this->belongsTo(Car::class, 'id_car');
    }
}
