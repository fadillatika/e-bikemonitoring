<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
// use App\Models\Motor;

class Battery extends Model
{
    use HasFactory;
    protected $fillable = [
        'motor_id', 'percentage', 'kilometers', 'time', 'voltage'
    ];

    protected static function booted()
    {
        static::creating(function ($battery) {
            $lastBattery = Battery::where('created_at', '<', $battery->created_at)
                ->latest('created_at')
                ->first();

            if ($lastBattery) {
                $battery->time = $battery->created_at->diffInSeconds($lastBattery->created_at);
            } else {
                $battery->time = 0;
            }

            Log::info('Battery time calculated', ['time' => $battery->time]);
        });
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
}
