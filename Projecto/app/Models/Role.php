<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    // Relación con usuarios
    public function user()
    {
        return $this->hasMany(User::class, 'id_role');
    }

    // Si tenés settings relacionados
    public function settings()
    {
        return $this->hasMany(Settings::class, 'id_roles');
    }
}
