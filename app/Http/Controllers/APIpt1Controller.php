<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Tracking;
use App\Models\Battery;
use App\Models\Lock;
use App\Events\MonitorUpdated;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Apipt1Controller extends Controller
{
    // public function addMotor(Request $request)
    // {
    //     $request->validate([
    //         'motors_id' => 'required|string|unique:motors,motors_id',
    //     ]);

    //     $motor = new Motor;
    //     $motor->motors_id = $request->motors_id; 
    //     $motor->save();

    //     return response()->json($motor, 201);
    // }

    // public function addTracking(Request $request)
    // {
    //     $request->validate([
    //         'motor_id' => 'required|exists:motors,id',
    //         'latitude' => 'required',
    //         'longitude' => 'required',
    //     ]);

    //     $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
    //     if (!$motor) {
    //         return response()->json(['error' => 'Motor not found.'], 404);
    //     }
        
    //     $previousTracking = Tracking::where('motor_id', $motor->id)
    //                             ->latest()
    //                             ->first();

    //     $tracking = new Tracking;
    //     $tracking->motor_id = $request->motor_id;
    //     $tracking->latitude = $request->latitude;
    //     $tracking->longitude = $request->longitude;

    //     if ($previousTracking) {
    //         $distance = $this->haversine($previousTracking->latitude, $previousTracking->longitude, $request->latitude, $request->longitude);
    //         $tracking->distance = $distance;
    //         $tracking->total_distance = $previousTracking->total_distance + $distance;
    //     } else {
    //         $tracking->distance = 0; 
    //         $tracking->total_distance = 0;
    //     }
        
    //     $tracking->save();

    //     $this->updateLockTripDistance($motor, $tracking);

    //     event(new MonitorUpdated($motor));

    //     return response()->json($tracking, 201);
    // }

    // public function addBattery(Request $request)
    // {
    //     $request->validate([
    //         'motor_id' => 'required|exists:motors,id',
    //         'percentage' => 'required|numeric|between:0,100',
    //         'voltage' => 'required|numeric',
    //         'current' => 'required|numeric',
    //         'kilometers' => 'required|numeric|min:0',
            
    //     ]);

    //     $battery = new Battery;
    //     $battery->motor_id = $request->motor_id;
    //     $battery->percentage = $request->percentage;
    //     $battery->voltage = $request->voltage;
    //     $battery->current = $request->current;
    //     $battery->kilometers = $request->kilometers;
    //     $battery->save();
        
    //     $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
    //     event(new MonitorUpdated($motor));

    //     return response()->json($battery, 201);
    // }

    // public function addLock(Request $request)
    // {
    //     $request->validate([
    //         'motor_id' => 'required|exists:motors,id',
    //         'status' => 'boolean',
    //     ]);
    
    //     $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
    //     if (!$motor) {
    //         return response()->json(['error' => 'Motor not found.'], 404);
    //     }
    
    //     $activeLock = Lock::where('motor_id', $motor->id)
    //                      ->orderBy('created_at', 'desc')
    //                      ->first();
    
    //     if ($request->status) {
    //         if (!$activeLock || !$activeLock->status) {
    //             $lock = new Lock;
    //             $lock->motor_id = $request->motor_id;
    //             $lock->status = 1;
    //             $lock->trip_distance = 0;
    //             $lock->save();
    //         }
    //     } else {
    //         if ($activeLock && $activeLock->status) {
    //             $activeLock->status = 0;
    //             $lastTracking = Tracking::where('motor_id', $motor->id)
    //                                 ->latest()
    //                                 ->first();
                
    //             if ($lastTracking) {
    //                 $activeLock->trip_distance = $this->calculateTripDistance($motor, $activeLock, $lastTracking);
    //             }
    //             $activeLock->save();
    //         }
    //     } 

    //     event(new MonitorUpdated($motor));
    
    //     return response()->json($activeLock ?? $lock, 201);
    // }

    // private function calculateTripDistance($motor, $lock, $lastTracking)
    // {
    // $tripDistance = 0;
    // $trackings = Tracking::where('motor_id', $motor->id)
    //                      ->where('created_at', '>=', $lock->created_at)
    //                      ->get();

    // foreach ($trackings as $tracking) {
    //     $tripDistance += $tracking->distance;
    // }

    // return $tripDistance;
    // }

    // private function updateLockTripDistance($motor, $tracking)
    // {
    //     $activeLock = Lock::where('motor_id', $motor->id)
    //                      ->where('status', 1)
    //                      ->latest()
    //                      ->first();

    //     if ($activeLock) {
    //         $activeLock->trip_distance += $tracking->distance;
    //         $activeLock->save();
    //     }
    // }

    // private function haversine($lat1, $lon1, $lat2, $lon2)
    // {
    //     $earth_radius = 6371; // radius bumi dalam kilometer

    //     $dLat = deg2rad($lat2 - $lat1);
    //     $dLon = deg2rad($lon2 - $lon1);

    //     $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    //     $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    //     $distance = $earth_radius * $c;

    //     return $distance; // jarak dalam kilometer
    // }
}