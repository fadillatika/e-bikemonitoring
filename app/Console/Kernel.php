<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\FetchThingSpeaksData;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('fetch:thingspeak')->everyTwoSeconds();
        $schedule->job(new FetchThingSpeaksData)->everyTwoSeconds();
    }

    /**
     * Register the commands for the application.
     */

    // protected $commands = [
    //     \App\Console\Commands\FetchThingSpeakData::class,
    // ];
    
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
