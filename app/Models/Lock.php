<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    public function motors()
    {
        return $this->belongsTo(Motor::class, 'motors_id');
    }
    use HasFactory;
}
