<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Tracking;
use App\Models\Battery;
use App\Models\Lock;

class ApiController extends Controller
{
    public function addMotor(Request $request)
    {
        $request->validate([
            'motors_id' => 'required|string|unique:motors,motors_id',
        ]);

        $motor = new Motor;
        $motor->motors_id = $request->motors_id; 
        $motor->save();

        return response()->json($motor, 201);
    }

    public function addTracking(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'recorded_at' => 'required|date',
        ]);

        $tracking = new Tracking;
        $tracking->motor_id = $request->motor_id;
        $tracking->latitude = $request->latitude;
        $tracking->longitude = $request->longitude;
        $tracking->recorded_at = $request->recorded_at;
        $tracking->save();

        return response()->json($tracking, 201);
    }

    public function addBattery(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'percentage' => 'required|integer|between:0,100',
            'kilometers' => 'required|integer|min:0',
            'kW' => 'required|integer|min:0'
        ]);

        $battery = new Battery;
        $battery->motor_id = $request->motor_id;
        $battery->percentage = $request->percentage;
        $battery->kilometers = $request->kilometers;
        $battery->kW = $request->kW;
        $battery->save();

        return response()->json($battery, 201);
    }

    public function addLock(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'status' => 'required|boolean',
        ]);

        $lock = new Lock;
        $lock->motor_id = $request->motor_id;
        $lock->status = $request->status;
        $lock->save();

        return response()->json($lock, 201);
    }

    public function getTrackings(){
        $trackings = Tracking::select('trackings.latitude', 'trackings.longitude', 'motors.motors_id', 'trackings.created_at')
            ->join('motors', 'trackings.motor_id', '=', 'motors.id')
            ->whereRaw('trackings.created_at IN (SELECT MAX(created_at) FROM trackings GROUP BY motor_id)')
            ->get();
        return response()->json($trackings);
    }    
}