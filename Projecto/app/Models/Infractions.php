<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infractions extends Model
{
   use HasFactory;

    public function inspectors(){
        return $this->belongsTo(Inspectors::class, 'id_inspector');
    }

    public function cars(){
        return $this->belongsTo(Cars::class, 'id_cars');
    }
}
