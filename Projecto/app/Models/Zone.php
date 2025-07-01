<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    public function streets()
    {
        return $this->hasMany(Street::class, 'id_zone');
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'id_zone');
    }
}

