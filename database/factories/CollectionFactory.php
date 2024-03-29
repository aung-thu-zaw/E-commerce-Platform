<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->unique()->sentence(4),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->unique()->sentence(),
            'created_at' => fake()->dateTimeBetween('-9 months', now()),
        ];
    }
}
