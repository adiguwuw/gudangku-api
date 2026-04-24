<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'sku' => strtoupper(fake()->bothify('SKU-###??')),
            'barcode' => fake()->ean13(),
            'unit' => 'pcs',
            'price' => fake()->numberBetween(1000, 100000),
            'cost_price' => fake()->numberBetween(500, 50000),
            'minimum_stock' => fake()->numberBetween(5, 20),
            'brand' => fake()->company(),
            'weight' => fake()->randomFloat(2, 0.1, 5),
            'description' => fake()->sentence(),
        ];
    }
}