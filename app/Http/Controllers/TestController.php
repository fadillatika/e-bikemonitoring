<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function testDistance()
    {
        $lat1 = -6.971482;
        $lon1 = 107.630730;
        $lat2 = -6.971342;
        $lon2 = 107.631294;

        $distance = $this->calculateDistanceUsingOSRM($lat1, $lon1, $lat2, $lon2);
        Log::info('Calculated distance: ' . $distance);
    }

    private function calculateDistanceUsingOSRM($lat1, $lon1, $lat2, $lon2)
    {
        $client = new Client();
        $url = "http://router.project-osrm.org/route/v1/driving/{$lon1},{$lat1};{$lon2},{$lat2}?overview=false";

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);
            return $data['routes'][0]['distance'];
        } catch (\Exception $e) {
            Log::error("Error fetching distance from OSRM: " . $e->getMessage());
            return 0;
        }
    }
}
