<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function index()
    {
        $motor = Motor::with([
            'batteries' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'locks' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'trackings' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();
        
        // Convert and set location name
        $motor->each(function ($motorItem) {
            $motorItem->trackings->each(function ($tracking) {
                $tracking->location_name = $this->getLocationName($tracking->latitude, $tracking->longitude);
            });
        });

        return view('data', compact('motor'));
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
}
