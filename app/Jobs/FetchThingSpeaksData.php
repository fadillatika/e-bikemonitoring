<?php

namespace App\Jobs;

use App\Http\Controllers\ApiController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchThingSpeaksData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('FetchThingSpeaksData job started');

        try {
            $apiController = new ApiController();
            $iterations = 6;
            $interval = 10;

            for ($i = 0; $i < 30; $i++) {
                Log::info('Mengambil data GPS');
                $apiController->fetchTSGPS();
                Log::info('Mengambil data Battery');
                $apiController->fetchTSBattery();
                Log::info('Mengambil data Lock');
                $apiController->fetchTSLock();

                if ($i < $iterations - 1) {
                    sleep($interval);
                }
            }
            
            Log::info('FetchThingSpeaksData job selesai dengan sukses');
        } catch (\Exception $e) {
            Log::error('FetchThingSpeaksData job gagal: ' . $e->getMessage());
        }
    }
}