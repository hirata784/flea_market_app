<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SellFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 2),
            'item_id' => $this->faker->numberBetween(11, 20),
        ];
    }
}
