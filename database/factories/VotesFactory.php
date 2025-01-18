<?php

namespace Database\Factories;

class VotesFactory
{
    public function definition(): array
    {
        return [
            'likes' => fake()->numberBetween(0, 100),
            'unlikes' => fake()->numberBetween(0, 100),
        ];
    }
}
