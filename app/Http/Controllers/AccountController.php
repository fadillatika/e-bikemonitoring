<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AccountController extends Controller
{
    public function account(Request $request)
    {
        $query = $request->input('q');
        $dataNotFound = false;
        $latestBatteryData = null;
        $latestLock = null;
        $latestLocation = null;
        $motorId = $request->session()->get('motor_id', null); // Dapatkan motor_id dari sesi
    
        $motorsId = null;
        if ($motorId) {
            $motor = Motor::find($motorId);
            if ($motor) {
                $motorsId = $motor->motors_id;
            }
        }
    
        if (!empty($query)) {
            $motors = Motor::where('motors_id', 'like', "%{$query}%")
                ->with(['batteries' => function($q) {
                    $q->orderBy('created_at', 'desc');
                }, 'locks'=>function($q){
                    $q->orderBy('created_at', 'desc');
                }, 'trackings'=>function($q){
                    $q->orderBy('created_at', 'desc');
                }])->get();
    
            if ($motors->isEmpty()) {
                $dataNotFound = true;
            } else {
                $latestBatteryData = $motors->first()->batteries->first();
                $latestLock = $motors->first()->locks->first();
                $latestLocation = $motors->first()->trackings->first();
            }
        } else {
            $dataNotFound = true;
            $motors = collect();
        }
    
        return view('account', compact('motors', 'latestLocation', 'latestBatteryData', 'dataNotFound', 'latestLock', 'motorsId'));
    }       
}