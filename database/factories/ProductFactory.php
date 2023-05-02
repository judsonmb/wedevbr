<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Merchant;

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
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'merchant_id' => Merchant::inRandomOrder()->first()->id,
            'price' => fake()->randomNumber(2),
            'status' => fake()->randomElement(['out_of_stock', 'in_stock', 'running_low']),
        ];
    }
}
