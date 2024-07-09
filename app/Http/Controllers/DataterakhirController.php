<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\Lock;
use App\Models\Tracking;
use Illuminate\Http\Request;

class DataterakhirController extends Controller
{
    public function index(Request $request)
    {
        $motorId = $request->input('motor_id');

        $battery = $this->battery($motorId);
        $lock = $this->lock($motorId);
        $tracking = $this->tracking($motorId);

        return response()->json([
            'battery' => $battery,
            'lock' => $lock,
            'tracking' => $tracking
        ]);
    }

    public function battery($motorId = null)
    {
        $query = Battery::query();
        if ($motorId) {
            $query->where('motor_id', $motorId);
        }

        $latestBattery = $query->orderBy('created_at', 'desc')->first();

        return $latestBattery ? [
            'percentage' => $latestBattery->percentage,
            'kilometers' => $latestBattery->kilometers
        ] : null;
    }

    public function lock($motorId = null)
    {
        $query = Lock::query();
        if ($motorId) {
            $query->where('motor_id', $motorId);
        }

        $latestLock = $query->orderBy('created_at', 'desc')->first();

        return $latestLock ? [
            'status' => $latestLock->status
        ] : null;
    }

    public function tracking($motorId = null)
    {
        $query = Tracking::query();
        if ($motorId) {
            $query->where('motor_id', $motorId);
        }

        $latestTracking = $query->orderBy('created_at', 'desc')->first();

        return $latestTracking ? [
            'latitude' => $latestTracking->latitude,
            'longitude' => $latestTracking->longitude,
            'distance' => $latestTracking->distance,
            'total_distance' => $latestTracking->total_distance
        ] : null;
    }
}
