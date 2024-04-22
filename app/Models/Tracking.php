<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Motor;

class Tracking extends Model
{
    public function motors()
    {
        return $this->belongsTo(Motor::class, 'motors_id');
    }
    use HasFactory;

    protected $table = 'trackings';

    protected $fillable = [
        'motor_id',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];
}
