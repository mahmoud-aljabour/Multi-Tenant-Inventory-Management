<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'tenant_id' => Tenant::factory(),
            'price' => fake()->randomFloat(2, 1, 500),
            'low_stock_threshold' => fake()->numberBetween(1, 20),
            'quantity' => 0,
        ];
    }
}
