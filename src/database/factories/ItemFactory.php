<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomNumber(),
            'description' => $this->faker->sentence(),
            'img_url' => $this->faker->word(),
            'condition' => $this->faker->word(),
            'brand' => $this->faker->word(),
        ];
    }
}
