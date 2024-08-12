<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        $categories = \App\Models\Category::all();
        return [
            //
            //
            "name" => $this->faker->name,
            "description" => $this->faker->text,
            "price" => $this->faker->randomFloat(2, 1, 100),
            "category_id" => $categories->random()->id,
            "stock" => $this->faker->randomNumber(2),
            "image_url" => "https://via.placeholder.com/150",
        ];
    }
}
