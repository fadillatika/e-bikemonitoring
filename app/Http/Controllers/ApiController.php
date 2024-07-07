<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Tracking;
use App\Models\Battery;
use App\Models\Lock;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    public function fetchTSGPS()
    {
        $motorChannels = [
            ['id' => '2538975', 'apiKey' => '88QJUVVUHZPNI4HC', 'motor_id' => 1],
        ];

        $client = new Client();

        foreach ($motorChannels as $channel) {
            $url = "https://api.thingspeak.com/channels/{$channel['id']}/feeds.json?api_key={$channel['apiKey']}";

            try {
                $response = $client->get($url);
                $data = json_decode($response->getBody(), true);

                foreach ($data['feeds'] as $feed) {
                    if (isset($feed['field1'], $feed['field2'], $feed['created_at'])) {
                        $latitude = floatval($feed['field1']);
                        $longitude = floatval($feed['field2']);

                        // Periksa apakah nilai latitude dan longitude adalah 0.0000000
                        if ($latitude == 0.0000000 && $longitude == 0.0000000) {
                            Log::warning('GPS data is 0.0000000, skipping this entry.');
                            continue;
                        }

                        $timestamp = Carbon::parse($feed['created_at']);
                        $existingTracking = Tracking::where('motor_id', $channel['motor_id'])
                            ->where('created_at', $timestamp)
                            ->first();

                        if (!$existingTracking) {
                            $latestTracking = Tracking::where('motor_id', $channel['motor_id'])
                                ->latest()
                                ->first();

                            $tracking = new Tracking;
                            $tracking->motor_id = $channel['motor_id'];
                            $tracking->latitude = $latitude;
                            $tracking->longitude = $longitude;
                            $tracking->created_at = $timestamp;

                            if ($latestTracking) {
                                $distance = $this->haversine($latestTracking->latitude, $latestTracking->longitude, $tracking->latitude, $tracking->longitude);

                                // Validasi jarak sebelum disimpan
                                if ($distance <= PHP_FLOAT_MAX) {
                                    $tracking->distance = $distance;
                                    $tracking->total_distance = $latestTracking->total_distance + $distance;
                                } else {
                                    Log::error('Jarak yang dihitung melebihi batas yang diizinkan.');
                                    // Tindakan penanganan kesalahan lainnya, seperti memberi tahu pengguna atau mengatur nilai default.
                                    $tracking->distance = 0;
                                    $tracking->total_distance = $latestTracking->total_distance;
                                }
                            } else {
                                $tracking->distance = 0;
                                $tracking->total_distance = 0;
                            }

                            $tracking->save();
                            Log::info('Saved tracking entry:', $tracking->toArray());

                            // Update trip distance
                            $motor = Motor::find($channel['motor_id']);
                            $this->updateLockTripDistance($motor, $tracking);
                        } else {
                            Log::info('Data already exists for timestamp: ' . $timestamp);
                        }
                    } else {
                        Log::warning('Incomplete GPS Data:', $feed);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data from ThingSpeak: " . $e->getMessage());
            }
        }
    }

    public function fetchTSBattery()
    {
        $motorChannels = [
            ['id' => '2538975', 'apiKey' => '88QJUVVUHZPNI4HC', 'motor_id' => 1],
        ];

        $client = new Client();

        foreach ($motorChannels as $channel) {
            $url = "https://api.thingspeak.com/channels/{$channel['id']}/feeds.json?api_key={$channel['apiKey']}";

            try {
                $response = $client->get($url);
                $data = json_decode($response->getBody(), true);

                foreach ($data['feeds'] as $feed) {
                    if (isset($feed['field4'], $feed['field5'], $feed['field6'], $feed['created_at'])) {
                        $timestamp = Carbon::parse($feed['created_at']);
                        $existingBattery = Battery::where('motor_id', $channel['motor_id'])
                            ->where('created_at', $timestamp)
                            ->first();

                        if (!$existingBattery) {
                            $battery = new Battery;
                            $battery->motor_id = $channel['motor_id'];
                            $battery->percentage = $feed['field6'];
                            $battery->voltage = $feed['field4'];
                            $battery->kilometers = 0;
                            $battery->created_at = $timestamp;
                            $battery->save();
                        } else {
                            Log::info('Battery data already exists for timestamp: ' . $timestamp);
                        }
                    } else {
                        Log::warning('Incomplete Battery Data:', $feed);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data from ThingSpeak: " . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Battery data fetched and stored.']);
    }

    public function fetchTSLock()
    {
        $motorChannels = [
            ['id' => '2538975', 'apiKey' => '88QJUVVUHZPNI4HC', 'motor_id' => 1],
        ];

        $client = new Client();

        foreach ($motorChannels as $channel) {
            $url = "https://api.thingspeak.com/channels/{$channel['id']}/feeds.json?api_key={$channel['apiKey']}";

            try {
                $response = $client->get($url);
                $data = json_decode($response->getBody(), true);

                foreach ($data['feeds'] as $feed) {
                    if (isset($feed['field3'], $feed['created_at'])) {
                        $timestamp = Carbon::parse($feed['created_at']);

                        $status = (int) $feed['field3'];

                        if (!in_array($status, [0, 1])) {
                            Log::warning('invalid status value:', ['status' => $status]);
                            continue;
                        }

                        $existingLock = Lock::where('motor_id', $channel['motor_id'])
                            ->where('created_at', $timestamp)
                            ->first();

                        if (!$existingLock) {
                            $lock = new Lock;
                            $lock->motor_id = $channel['motor_id'];
                            $lock->status = $status;
                            $lock->trip_distance = 0; // Set trip_distance to 0 when creating a new lock entry
                            $lock->created_at = $timestamp;
                            $lock->save();
                        } else {
                            Log::info('Lock data already exists for timestamp: ' . $timestamp);
                        }
                    } else {
                        Log::warning('Incomplete Lock Data:', $feed);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data from ThingSpeak: " . $e->getMessage());
            }
        }
    }


    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371; // radius bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earth_radius * $c;

        return $distance;
    }

    private function updateLockTripDistance($motor, $tracking)
    {
        $activeLock = Lock::where('motor_id', $motor->id)
            ->where('status', 1)
            ->latest()
            ->first();

        if ($activeLock) {
            $activeLock->trip_distance += $tracking->distance;
            $activeLock->save();
        }
    }

    private function calculateTripDistance($motor, $lock, $lastTracking)
    {
        $tripDistance = 0;
        $trackings = Tracking::where('motor_id', $motor->id)
            ->where('created_at', '>=', $lock->created_at)
            ->get();

        foreach ($trackings as $tracking) {
            $tripDistance += $tracking->distance;
        }

        return $tripDistance;
    }

    public function addLock(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'status' => 'boolean',
        ]);

        $motor = Motor::with(['batteries', 'locks', 'trackings'])->find($request->motor_id);
        if (!$motor) {
            return response()->json(['error' => 'Motor not found.'], 404);
        }

        $activeLock = Lock::where('motor_id', $motor->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($request->status) {
            if (!$activeLock || !$activeLock->status) {
                $lock = new Lock;
                $lock->motor_id = $request->motor_id;
                $lock->status = 1;
                $lock->trip_distance = 0;
                $lock->save();
            }
        } else {
            if ($activeLock && $activeLock->status) {
                $activeLock->status = 0;
                $lastTracking = Tracking::where('motor_id', $motor->id)
                    ->latest()
                    ->first();

                if ($lastTracking) {
                    $activeLock->trip_distance = $this->calculateTripDistance($motor, $activeLock, $lastTracking);
                }
                $activeLock->save();
            }
        }

        return response()->json($activeLock ?? $lock, 201);
    }
}
