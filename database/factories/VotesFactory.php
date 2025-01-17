<?php

namespace Database\Factories;

class VotesFactory
{
    public function definition(): array
    {
        return [
            'likes' => fake()->numberBetween(0, 100),
            'unlike' => fake()->numberBetween(0, 100),
        ];
    }
}
