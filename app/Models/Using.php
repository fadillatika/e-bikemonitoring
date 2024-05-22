<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Using extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'motor_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function motors()
    {
        return $this->belongsTo(Motor::class, 'motors_id');
    }
}
