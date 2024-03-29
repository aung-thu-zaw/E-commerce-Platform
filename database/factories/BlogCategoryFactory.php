<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->unique()->name(),
            'slug' => fake()->unique()->slug(),
            'image' => fake()->imageUrl(),
            'status' => 'show',
            'created_at' => fake()->dateTimeBetween('-9 months', now()),
        ];
    }
}
