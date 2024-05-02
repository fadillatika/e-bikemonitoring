<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $dataNotFound = false;
        $latestBatteryData = null;
        $latestLock = null;
        if (!empty($query)) {
            // Mencari data motor berdasarkan ID
            $motors = Motor::where('motors_id', 'like', "%{$query}%")
                ->with(['batteries' => function($q) {
                    $q->orderBy('last_charged_at', 'desc');
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
                $motors = $this->location($motors); //geocode
            }

            $locationsForMap = $motors->map(function ($motor) {
                if ($motor->trackings->isNotEmpty()) {
                }
            })->filter()->values();
        } else {
            $dataNotFound = true;
            $motors = collect();
            $locationsForMap = collect();
        }
        return view('search', compact('motors', 'locationsForMap', 'latestBatteryData', 'dataNotFound', 'latestLock'));
    }

    // Fungsi location dan getLocationName tetap seperti sebelumnya
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
        $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
            'format' => 'json',
            'lat' => $latitude,
            'lon' => $longitude,
        ]);

        $data = $response->json();
        return $data['display_name'] ?? 'Lokasi tidak ditemukan';
    }
}
