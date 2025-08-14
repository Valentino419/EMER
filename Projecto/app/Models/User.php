<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    use hasFactory;

    public function settings()
    {
        return $this->hasOne(Setting::class, 'user_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }
    public function cars()
    {
        return $this->hasMany(Car::class, 'user_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
      public function role()
    {
        return $this->belongsTo(Role::class,'id_role');
    } 
    public function infractions()
    {
        return $this->hasMany(Infraction::class, 'id_user');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $fillable = [
        'name',
        'surname',
        'email',
        'dni',
        'password', 
        'role',

    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
