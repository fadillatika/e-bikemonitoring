<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
    use HasFactory;

    protected $fillable = [
        'motor_id',
        'status',
        'trip_distance'
    ];
}
