<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

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
            'name' => 'Order ' . $this->faker->unique()->numberBetween(1, 1000),
            'product' => $this->faker->randomElement([
                'iPhone',
                'MacBook',
                'iPad',
                'AirPods'
            ]),
            'price' => $this->faker->numberBetween(100, 3000),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }

}
