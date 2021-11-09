<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return[
            'user_id'=> $this->faker->numberBetween(1, User::count()),
            'name'=>$this->faker->unique()->word,
            'quantity'=>$this->faker->randomNumber(4)
        ];
    }
}
