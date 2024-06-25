<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    public function motors()
    {
        return $this->belongsTo(Motor::class, 'motors_id');
    }

    use Notifiable;

    protected $fillable = [
        'username', 'password', 'email', 'motor_id'
    ];

    protected $hidden = [
        'password',
    ];
}
