<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSession extends Model
{
    protected $fillable = [
        'user_id', 'car_id', 'zone_id', 'street_id', 'license_plate',
        'start_time', 'end_time', 'duration', 'rate', 'amount',
        'payment_status', 'status', 'metodo_pago', 'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
