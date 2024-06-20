<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ApiController;

class FetchTSData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:tsdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiController = new ApiController;

        $apiController->fetchTSGPS();
        $apiController->fetchTSBattery();
        $apiController->fetchTSLock();

        $this->info('Data fetched successfully from ThingSpeak.');
    }
}
