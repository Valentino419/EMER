<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends Model
{
    use HasFactory;

    public function users(){
       
        return $this->belongsTo(Users::class, 'user_id');
    }
}
