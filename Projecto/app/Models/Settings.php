<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends Model
{
    use HasFactory;

    public function users()
    {
       return $this->belongsTo(User::class,'id_users');        
    }

    public function rolers()
    {
        return $this->belongsTo(Roles::class,'id_roles');
    }

}
