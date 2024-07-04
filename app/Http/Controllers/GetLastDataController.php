<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\Lock;
use App\Models\Tracking;
use Illuminate\Http\Request;

class GetLastDataController extends Controller
{
    public function index()
    {
        $battery = $this->battery();
        $lock = $this->lock();
        $tracking = $this->tracking();
        return response()->json([
            'battery' => $battery,
            'lock' => $lock,
            'tracking' => $tracking
        ]);
    }

    public function battery()
    {
        $latestBattery = Battery::orderBy('created_at', 'desc')->first();
        return $latestBattery ? [
            'percentage' => $latestBattery->percentage,
            'kilometers' => $latestBattery->kilometers
        ] : null;
    }
    public function lock()
    {
        $latestLock = Lock::orderBy('created_at', 'desc')->first();
        return $latestLock ? [
            'status' => $latestLock->status
        ] : null;
    }
    public function tracking()
    {
        $latestTracking = Tracking::orderBy('created_at', 'desc')->first();
        return $latestTracking ? [
            'latitude' => $latestTracking->latitude,
            'longitude' => $latestTracking->longitude,
            'distance' => $latestTracking->distance,
            'total_distance' => $latestTracking->total_distance
        ] : null;
    }
}
