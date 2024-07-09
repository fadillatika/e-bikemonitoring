<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        Log::info('Search query: ' . $query);
        $dataNotFound = false;
        $latestBatteryData = null;
        $latestLock = null;
        if (!empty($query)) {
            $motors = Motor::where('motors_id', 'like', "%{$query}%")
                ->with(['batteries' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                }, 'locks' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                }, 'trackings' => function ($q) {
                    $q->orderBy('created_at', 'desc'); // urutkan dari yang paling awal
                }])->get();

            if ($motors->isEmpty()) {
                $dataNotFound = true;
            } else {
                Log::info('Motor found' . $motors->first()->motors_id);
                $latestBatteryData = $motors->first()->batteries->first();
                $latestLock = $motors->first()->locks->first();
                $motors = $this->location($motors);
            }

            $locationsForMap = $motors->map(function ($motor) {
                return $motor->trackings->map(function ($tracking) use ($motor) {
                    return [
                        'lat' => $tracking->latitude,
                        'lng' => $tracking->longitude,
                        'name' => $tracking->location_name,
                        'motorName' => $motor->motors_id
                    ];
                });
            })->flatten(1);
        } else {
            $dataNotFound = true;
            $motors = collect();
            $locationsForMap = collect();
        }

        Log::info('Motors found: ' . $motors->count());
        Log::info('Latest Battery Data: ' . json_encode($latestBatteryData));
        Log::info('Latest Lock Data: ' . json_encode($latestLock));

        return view('search', compact('motors', 'locationsForMap', 'latestBatteryData', 'dataNotFound', 'latestLock'));
    }

    public function location($motors)
    {
        $motors->each(function ($motorItem) {
            $motorItem->trackings->each(function ($tracking) {
                $tracking->location_name = $this->getLocationName($tracking->latitude, $tracking->longitude);
            });
        });

        return $motors;
    }

    protected function getLocationName($latitude, $longitude)
    {
        $cacheKey = "location_{$latitude}_{$longitude}";
        return Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'format' => 'json',
                'lat' => $latitude,
                'lon' => $longitude,
            ]);

            $data = $response->json();
            return $data['display_name'] ?? 'Lokasi tidak ditemukan';
        });
    }
}
