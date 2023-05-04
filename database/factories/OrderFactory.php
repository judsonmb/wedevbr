<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => fake()->word()
        ];
    }

    /**
     * To create the order_item.
     */
    public function toCreateOrderItem(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'product_id' => Product::inRandomOrder()->first()->id,
                'quantity' => fake()->randomNumber(2)
            ];
        });
    }
}
