<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedules extends Model
{
    use HasFactory;

    public function zones(){
        return $this->belongsTo(Zones::class, 'id_zone');
    }
}
