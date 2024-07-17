<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('fetch:tsdata')->everyMinute();
        $schedule->command('app:update-battery-time')->everyMinute();
        $schedule->command('prediction:run')->everyMinute();
        $schedule->command('app:delete7-days-data')->weeklyOn(1, '00:00');


        // $schedule->command('generate:dummy-battery-data')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
