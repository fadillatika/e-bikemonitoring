<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Tracking;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index()
    {
        $motor = Motor::with(['batteries'=> function($query){
            $query->orderBy('created_at', 'desc');
        }, 'locks'=>function($query){
            $query->orderBy('created_at', 'desc');
        }, 'trackings'=>function($query){
            $query->orderBy('created_at', 'desc');
        }])->get();
        
        // konversi
        $motor->each(function ($motorItem) {
            $motorItem->trackings->each(function ($tracking) {
                $tracking->location_name = $this->getLocationName($tracking->latitude, $tracking->longitude);
            });
        });

         return view('spesific', compact('motor'));
    }


    protected function getLocationName($latitude, $longitude)
    {
        $cacheKey = "location_{$latitude}_{$longitude}";
        return cache()->remember($cacheKey, 3600, function () use ($latitude, $longitude) {
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'format' => 'json',
                'lat' => $latitude,
                'lon' => $longitude,
            ]);

            $data = $response->json();
            return $data['display_name'] ?? 'Lokasi tidak ditemukan';
        });
    }

    public function getTrackings()
    {
        $trackings = Tracking::select('trackings.latitude', 'trackings.longitude', 'motors.motors_id', 'trackings.created_at', 'locks.status')
            ->join('motors', 'trackings.motor_id', '=', 'motors.id')
            ->leftJoin('locks', function($join) {
                $join->on('trackings.motor_id', '=', 'locks.motor_id')
                     ->whereRaw('locks.created_at = trackings.created_at');
            })
            ->orderBy('trackings.created_at')
            ->get();

        $countTrackings = $trackings->groupBy('motors_id')->map(function ($item) {
            return $item->map(function($tracking){
                return [
                    'latitude' => $tracking->latitude,
                    'longitude' => $tracking->longitude,
                    'created_at' => $tracking->created_at,
                    'status' => $tracking->status ? 'on' : 'off'
                ];
            });
        });
        return response()->json($countTrackings);
    }
}