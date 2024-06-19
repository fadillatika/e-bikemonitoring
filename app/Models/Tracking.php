<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Motor;

class Tracking extends Model
{
    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
    use HasFactory;

    protected $table = 'trackings';

    protected $fillable = [
        'motor_id',
        'latitude',
        'longitude',
        'distance',
        'total_distance',
    ];

    // protected $casts = [
    //     'recorded_at' => 'datetime',
    // ];
}
