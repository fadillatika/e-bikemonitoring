<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Motor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tracking>
 */
class TrackingFactory extends Factory
{
    public function definition(): array
    {
        return [
            //
            // 'motors_id'=> Motor::factory(),
            'latitude'=> $this->faker->latitude($min = -8.5, $max = -6.5),
            'longitude'=> $this->faker->longitude($min = 105, $max = 115),
            'recorded_at'=> $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s')
        ];
    }
}
