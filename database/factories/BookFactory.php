<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
                'title' => $this->faker->sentence(3),
                'author' => $this->faker->name(),
                'rating' => $this->faker->numberBetween(1, 5),
                'description' => $this->faker->sentence(30),
                'created_at' => now(),
                'updated_at' => now(),
            ];
    }
}
