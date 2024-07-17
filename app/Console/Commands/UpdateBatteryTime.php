<?php

namespace App\Console\Commands;

use App\Models\Battery;
use Illuminate\Console\Command;

class UpdateBatteryTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-battery-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert created_at to second';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batteries = Battery::orderBy('created_at')->get();
        $previousBattery = null;

        foreach ($batteries as $battery) {
            if ($previousBattery) {
                $battery->time = $battery->created_at->diffInSeconds($previousBattery->created_at);
            } else {
                $battery->time = 0;
            }
            $battery->save();
            $previousBattery = $battery;
        }

        $this->info('Convert time updated.');
    }
}
