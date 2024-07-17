<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Truncate7DaysData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete7-days-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete data older than 7 days from trackings, batteries, and locks tables';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        date_default_timezone_set('Asia/Jakarta');

        $cutOffDate = Carbon::now()->subDays(7);

        DB::table('trackings')->where('created_at', '<', $cutOffDate)->delete();
        DB::table('batteries')->where('created_at', '<', $cutOffDate)->delete();
        DB::table('locks')->where('created_at', '<', $cutOffDate)->delete();

        $this->info('Old data deleted successfully.');

        return 0;
    }
}
