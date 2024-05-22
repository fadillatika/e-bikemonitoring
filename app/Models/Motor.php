<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'motors_id',
    ];

    public function batteries()
    {
        return $this->hasMany(Battery::class, 'motor_id');
    }

    public function trackings()
    {
        return $this->hasMany(Tracking::class, 'motor_id');
    }

    public function locks()
    {
        return $this->hasMany(Lock::class, 'motor_id');
    }

    public function admins()
    {
        return $this->hasOne(Admin::class, 'motor_id');
    }
}