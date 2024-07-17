<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Battery;
use App\Models\Lock;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GetLastDataController extends Controller
{
    public function index(Request $request)
    {
        $motorsId = $request->input('motors_id');
        $selectedDate = $request->input('date');

        $motor = Motor::where('motors_id', $motorsId)->first();

        if (!$motor) {
            return response()->json([
                'error' => 'Motor tidak ditemukan'
            ], 404);
        }

        $trackingQuery = $motor->trackings()->orderBy('created_at', 'desc');

        if ($selectedDate) {
            $startOfDay = Carbon::parse($selectedDate)->startOfDay();
            $endOfDay = Carbon::parse($selectedDate)->endOfDay();
            $trackingQuery->whereBetween('created_at', [$startOfDay, $endOfDay]);
        } else {
            // Filter untuk hari ini
            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();
            $trackingQuery->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }

        $tracking = $trackingQuery->get();

        $battery = $this->battery($motor);
        $lock = $this->lock($motor);
        $lastDistance = $this->lastDistance($motor);

        return response()->json([
            'battery' => $battery,
            'lock' => $lock,
            'tracking' => $tracking,
            'last_distance' => $lastDistance,
        ]);
    }

    public function battery($motor)
    {
        return $motor->batteries()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function lock($motor)
    {
        return $motor->locks()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function lastDistance($motor)
    {
        return $motor->trackings()
            ->orderBy('created_at', 'desc')
            ->value('distance');
    }

    public function checkData()
    {
        $count = Tracking::count();
        return response()->json(['isEmpty' => $count === 0]);
    }
}
