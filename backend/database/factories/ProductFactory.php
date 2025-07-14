<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            'product_id'      => fake()->uuid(),
            'name'            => fake()->words(2, true),
            'description'     => fake()->sentence(),
            'price'           => fake()->randomFloat(2, 10, 300),
            'category'        => 'Roupas',
            'brand'           => 'Zara',
            'stock'           => fake()->numberBetween(1, 20),
            'image_url'       => fake()->imageUrl(),
            'updated_at_api'  => now(),
        ];

    }
}
