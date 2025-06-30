<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspectors extends Model
{
    use HasFactory;

    public function users(){
        return $this->BelongTo(User::class, 'id_user');
    }
    public function infractions(){
        return $this->hasMany(Infractions::class, 'id_inspector');
    }
}
