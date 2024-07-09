<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Motor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    public function downloadBatteryData(Request $request)
    {
        try {
            $motorId = $request->input('motor_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            Log::info('Download request received', [
                'motor_id' => $motorId,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            if (!$motorId) {
                Log::error('Motor ID not provided');
                return redirect()->back()->withErrors(['message' => 'Motor ID not found.']);
            }

            $motor = Motor::where('motors_id', $motorId)
                ->with(['batteries' => function ($query) use ($startDate, $endDate) {
                    if ($startDate) {
                        $query->where('updated_at', '>=', $startDate);
                    }
                    if ($endDate) {
                        $query->where('updated_at', '>=', $endDate);
                    }
                }])->first();

            if (!$motor) {
                Log::error('Motor data not found', ['motor_id' => $motorId]);
                return redirect()->back()->withErrors(['message' => 'Motor data not found.']);
            }

            $csvData = "ID E-bike,Date,Voltage,Percentage,Battery-Kilometers\n";

            foreach ($motor->batteries as $battery) {
                $csvData .= "\"{$motor->motors_id}\",\"{$battery->updated_at}\",\"{$battery->voltage} V\",\"{$battery->percentage}%\",\"{$battery->kilometers} km\"\n";
            }

            $filename = "battery_data_motor_{$motorId}.csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            Log::info('CSV data prepared for download', ['filename' => $filename]);

            return Response::make(rtrim($csvData, "\n"), 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error in downloadBatteryData', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['message' => 'An error occurred while processing the request.']);
        }
    }

    public function downloadTrackingData(Request $request)
    {
        try {
            $motorId = $request->input('motor_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            Log::info('Download request received', [
                'motor_id' => $motorId,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            if (!$motorId) {
                Log::error('Motor ID not provided');
                return redirect()->back()->withErrors(['message' => 'Motor ID not found.']);
            }

            $motor = Motor::where('motors_id', $motorId)
                ->with(['trackings', 'locks' => function ($query) use ($startDate, $endDate) {
                    if ($startDate) {
                        $query->where('updated_at', '>=', $startDate);
                    }
                    if ($endDate) {
                        $query->where('updated_at', '>=', $endDate);
                    }
                }])->first();

            if (!$motor) {
                Log::error('Motor data not found', ['motor_id' => $motorId]);
                return redirect()->back()->withErrors(['message' => 'Motor data not found.']);
            }

            $csvData = "ID E-bike,Date,Location Name,Status\n";

            foreach ($motor->trackings as $tracking) {
                $locationName = $this->getLocationName($tracking->latitude, $tracking->longitude);
                $lockStatus = $motor->locks->isNotEmpty() ? ($motor->locks->first()->status ? 'Unlocked' : 'Locked') : 'No locks found';
                $csvData .= "\"{$motor->motors_id}\",\"{$tracking->updated_at}\",\"{$locationName}\",\"{$lockStatus}\"\n";
            }

            $filename = "tracking_data_motor_{$motorId}.csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            Log::info('CSV data prepared for download', ['filename' => $filename]);

            return Response::make(rtrim($csvData, "\n"), 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error in downloadBatteryData', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['message' => 'An error occurred while processing the request.']);
        }
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
