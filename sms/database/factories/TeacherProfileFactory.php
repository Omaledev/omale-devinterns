<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeacherProfile>
 */
class TeacherProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => 'EMP-' . fake()->unique()->numerify('#####'),
            'qualification' => fake()->jobTitle(),
            'specialization' => fake()->word(),
            'joining_date' => fake()->date(),
            'is_active' => true,
        ];
    }
}
