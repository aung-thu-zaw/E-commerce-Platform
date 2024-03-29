<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => fake()->uuid(),
            'avatar' => fake()->imageUrl(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'gender' => fake()->randomElement(['male', 'female']),
            'role' => fake()->randomElement(['user', 'seller']),
            'shop_name' => fake()->jobTitle(),
            'email_verified_at' => now(),
            'password' => bcrypt('Password!'),
            'remember_token' => Str::random(10),
            'created_at' => fake()->dateTimeBetween('-9 months', now()),
            'shop_name' => fake()->sentence(),
            'company_name' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
