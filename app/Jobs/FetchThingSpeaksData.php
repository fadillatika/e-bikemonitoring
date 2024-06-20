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
            for ($i = 0; $i < 30; $i++) {
                Log::info('Fetching GPS data');
                $apiController->fetchTSGPS();
                sleep(10);
            }
            
            Log::info('Fetching Battery data');
            $apiController->fetchTSBattery();
            
            Log::info('Fetching Lock data');
            $apiController->fetchTSLock();
            
            Log::info('FetchThingSpeaksData job completed successfully');
        } catch (\Exception $e) {
            Log::error('FetchThingSpeaksData job failed: ' . $e->getMessage());
        }
    }
}