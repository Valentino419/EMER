<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedules extends Model
{
    use HasFactory;

    public function zone(){
        return $this->belongsTo(Zone::class, 'id_zone');
    }
}
