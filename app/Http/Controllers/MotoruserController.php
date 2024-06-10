<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Tracking;
use App\Models\Lock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MotoruserController extends Controller
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

        return view('monitoruser', compact('motor'));
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

    // public function getTrackings()
    // {
    //     try {
    //         $trackings = Tracking::select('trackings.latitude', 'trackings.longitude', 'trackings.motor_id', 'trackings.created_at', 'locks.status as lock_status')
    //             ->join('motors', 'trackings.motor_id', '=', 'motors.id')
    //             ->leftJoin('locks', function($join) {
    //                 $join->on('trackings.motor_id', '=', 'locks.motor_id')
    //                     ->whereRaw('locks.created_at = (select max(`created_at`) from `locks` where `motor_id` = `trackings`.`motor_id`)');
    //             })
    //             ->orderBy('trackings.created_at')
    //             ->get();

    //         $countTrackings = $trackings->groupBy('motor_id')->map(function ($item) {
    //             return $item->map(function($tracking){
    //                 return [
    //                     'latitude' => $tracking->latitude,
    //                     'longitude' => $tracking->longitude,
    //                     'created_at' => $tracking->created_at,
    //                     'lock_status' => $tracking->lock_status
    //                 ];
    //             });
    //         });
            
    //         return response()->json($countTrackings);
    //     } catch (\Exception $e) {
    //         // Logging error for debugging
    //         Log::error("Error fetching trackings: " . $e->getMessage());
    //         return response()->json(['error' => 'Unable to fetch trackings'], 500);
    //     }
    // }
}