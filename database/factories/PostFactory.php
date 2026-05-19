<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(rand(4, 10)),
            'body' => fake()->paragraphs(rand(2, 5), true),
            'views' => fake()->numberBetween(0, 50000),
            'published_at' => fake()->optional(0.85)->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
