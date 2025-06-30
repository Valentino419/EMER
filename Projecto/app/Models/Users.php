<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Users extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    use hasFactory;
   
    public function inspectors(){
        return $this->hasOne(Inspectors::class, 'id_class');
    }
    public function settings(){
        return $this->hasOne(Settings::class, 'user_id');
    }
    public function payments(){
        return $this->hasMany(Payments::class, 'user_id');
    }
    public function cars(){
        return $this->hasMany(Cars::class, 'user_id');
    }
    public function notifications(){
        return $this->hasMany(Notifications::class, 'user_id');
    }
}
