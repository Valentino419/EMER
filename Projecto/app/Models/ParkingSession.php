<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ParkingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'zone_id',
        'street_id',
        'license_plate',
        'start_time',
        'end_time',
        'duration',
        'rate',
        'amount',
        'payment_id',
        'payment_status',
        'status',
        'metodo_pago',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'license_plate', 'license_plate')
            ->where('id_user', $this->user_id);
    }

    public function getDurationInHoursAttribute()
    {
        return $this->duration / 60;
    }

    public function getIsActiveAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return false;
        }

        return $this->status === 'active' &&
            now()->between($this->start_time, $this->end_time);
    }

    public function getIsExpiredAttribute()
    {
        return $this->status === 'expired' ||
            ($this->status === 'active' && now()->gt($this->end_time));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('payment_status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function canStart()
    {
        return $this->payment_status === 'completed' &&
            $this->status === 'pending';
    }

    public function expire()
    {
        if ($this->isActive) {
            $this->update([
                'status' => 'expired',
                'end_time' => now(),
            ]);
        }
    }
}
