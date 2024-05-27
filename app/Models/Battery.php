<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Motor;

class Battery extends Model
{
    use HasFactory;
    protected $fillable = [
        'motor_id', 'percentage', 'kilometers'
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
}
