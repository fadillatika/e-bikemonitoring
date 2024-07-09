<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Battery;
use App\Models\Lock;
use App\Models\Tracking;
use Illuminate\Http\Request;

class GetLastDataController extends Controller
{
    public function index(Request $request)
    {
        $motorsId = $request->input('motors_id');

        $motor = Motor::where('motors_id', $motorsId)->first();

        if (!$motor) {
            return response()->json([
                'error' => 'Motor tidak ditemukan'
            ], 404);
        }

        $battery = $this->battery($motor);
        $lock = $this->lock($motor);
        $tracking = $this->tracking($motor);

        return response()->json([
            'battery' => $battery,
            'lock' => $lock,
            'tracking' => $tracking
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

    public function tracking($motor)
    {
        return $motor->trackings()
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
