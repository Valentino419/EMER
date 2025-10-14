<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infraction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'car_id', 'fine', 'date', 'status'];

    // Inspector será un usuario con rol inspector
    public function inspector(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car(){
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
