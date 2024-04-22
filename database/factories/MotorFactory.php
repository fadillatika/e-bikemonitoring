<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Motor>
 */
class MotorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //manggil field yang ada di migrasi motor
            'motors_id' => $this->faker->unique()->regexify('[A-Z]{1,2}\d{1,4}[A-Z]{1,3}') //format plat motor Indo
        ];
    }
}
