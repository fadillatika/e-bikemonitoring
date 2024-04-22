<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Battery>
 */
class BatteryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
            // 'motors_id'=> Motor::factory(),
            'percentage'=> $this->faker->numberBetween(0,100),
            'kilometers'=> $this->faker->numberBetween(0, 5000),
            'kW'=> $this->faker->numberBetween(1, 1000),
            'last_charged_at'=> $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')
        ];
    }
}
