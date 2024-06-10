<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Tracking;
use App\Models\Battery;
use App\Models\Lock;
use App\Events\MonitorUpdated;
// use App\Events\TrackingUpdated;


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
        ]);

        $tracking = new Tracking;
        $tracking->motor_id = $request->motor_id;
        $tracking->latitude = $request->latitude;
        $tracking->longitude = $request->longitude;
        $tracking->save();

        $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
        event(new MonitorUpdated($motor));
        

        return response()->json($tracking, 201);
    }

    public function addBattery(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'percentage' => 'required|integer|between:0,100',
            'kilometers' => 'required|integer|min:0',
        ]);

        $battery = new Battery;
        $battery->motor_id = $request->motor_id;
        $battery->percentage = $request->percentage;
        $battery->kilometers = $request->kilometers;
        $battery->save();
        
        $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
        event(new MonitorUpdated($motor));

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

        $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
        event(new MonitorUpdated($motor));

        return response()->json($lock, 201);
    }    
}