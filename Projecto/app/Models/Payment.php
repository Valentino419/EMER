<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = ['license_plate', 'amount', 'payment_status', 'payment_id', 'metodo_pago', /* otros */];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
