<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Battery;
// use App\Models\Lock;
// use App\Models\Tracking;

// class GenerateDummyBatteryData extends Command
// {
//     protected $signature = 'generate:dummy-battery-data';
//     protected $description = 'Generate dummy data for Battery';

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function handle()
//     {
//         $this->info('Starting to generate dummy battery data...');

//         // BATTERY
//         $motorId = 1;
//         $percentage = rand(0, 100);
//         $kilometers = rand(0, 1000) / 10;

//         // LOCK
//         $status = rand(0, 1);

//         // TRACKING
//         $minLatitude = -6.95;
//         $maxLatitude = -6.85;
//         $minLongitude = 107.55;
//         $maxLongitude = 107.75;

//         $latitude = rand($minLatitude * 100000, $maxLatitude * 100000) / 100000;
//         $longitude = rand($minLongitude * 100000, $maxLongitude * 100000) / 100000;
//         $distance = rand(0, 100);
//         $total = rand(0, 10000);

//         $this->info("Generated values - Motor ID: $motorId, Percentage: $percentage, Kilometers: $kilometers");
//         Battery::create([
//             'motor_id' => $motorId,
//             'percentage' => $percentage,
//             'kilometers' => $kilometers
//         ]);

//         Lock::create([
//             'motor_id' => $motorId,
//             'status' => $status,
//         ]);

//         Tracking::create([
//             'motor_id' => $motorId,
//             'latitude' => $latitude,
//             'longitude' => $longitude,
//             'distance' => $distance,
//             'total_distance' => $total,
//         ]);
//         $this->info('Dummy battery data generated successfully.');
//     }
// }
