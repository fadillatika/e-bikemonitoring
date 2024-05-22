<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Motor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DownloadController extends Controller
{
    public function downloadData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $motorId = $request->session()->get('motor_id');

        if (!$motorId) {
            return redirect()->back()->withErrors(['message' => 'Motor ID not found.']);
        }

        $query = Motor::with(['batteries', 'locks', 'trackings']);

        if ($startDate && $endDate) {
            $query->whereHas('trackings', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        $motor = $query->find($motorId);

        if (!$motor) {
            return redirect()->back()->withErrors(['message' => 'Motor data not found.']);
        }

        $csvData = "ID E-bike,Date,Percentage,Battery-Kilometers,kiloWatt,Location,Status\n";

        $batteries = $motor->batteries;
        $locks = $motor->locks;
        $trackings = $motor->trackings->take($batteries->count());

        $motor = $this->convertLocationToJson($motor);

        foreach ($batteries as $index => $battery) {
            $tracking = $trackings[$index] ?? null;
            $lock = $locks[$index] ?? null;
            $dateToShow = $tracking ? $tracking->created_at : 'Data tidak ditemukan';
            $location = $tracking ? $tracking->location_name : 'Lokasi tidak ditemukan';
            $status = $lock ? ($lock->status ? 'On' : 'Off') : 'Status lock tidak ditemukan';

            $csvData .= "\"{$motor->motors_id}\",\"{$dateToShow}\",\"{$battery->percentage}%\",\"{$battery->kilometers} km\",\"{$battery->kW} kW\",\"{$location}\",\"{$status}\"\n";
        }

        $filename = $startDate && $endDate ? "motor_data_{$startDate}_to_{$endDate}.csv" : "motor_data_all.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return Response::make(rtrim($csvData, "\n"), 200, $headers);
    }

    public function convertLocationToJson($motor)
    {
        $motor->trackings->each(function ($tracking) {
            $tracking->location_name = $this->getLocationName($tracking->latitude, $tracking->longitude);
        });

        return $motor;
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