<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Motor;
use App\Models\Tracking;
use App\Models\Battery;
use App\Models\Lock;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
         Motor::factory()
        ->count(3) 
        ->has(Tracking::factory()->count(5), 'trackings') 
        ->has(Battery::factory()->count(5), 'batteries') 
        ->has(Lock::factory()->count(5), 'locks')
        ->create();
    }
}
