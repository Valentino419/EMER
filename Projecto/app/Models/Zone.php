<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    public function streets()
    {
        return $this->hasMany(Streets::class, 'id_zone');
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'id_zone');
    }
}