<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'label' => fake()->word(),
            'price' => fake()->randomNumber(3, false),
            'quantity' => fake()->randomNumber(3, false),
            'photo' => fake()->imageUrl(),
            'user_id' => User::all('id')->random(),
        ];
    }
}