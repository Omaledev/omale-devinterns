<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' High School',
            'address' => fake()->address(),
            'email' => fake()->unique()->companyEmail(), // Generates a safe fake email
            'phone' => fake()->phoneNumber(),
            'principal_name' => fake()->name(),
        ];
    }
}
