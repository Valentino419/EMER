<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    
    protected $fillable = ['zone_id', 'day_of_week', 'start_hour', 'end_hour'];
    
    public function zone(){
        return $this->belongsTo(Zone::class, 'id_zone');
    }
}
