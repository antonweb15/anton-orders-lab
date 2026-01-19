<?php

namespace Database\Factories;

use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierProductFactory extends Factory
{
    protected $model = SupplierProduct::class;

    public function definition(): array
    {
        return [
            'external_id' => 'SUP-' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(50, 2000),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
