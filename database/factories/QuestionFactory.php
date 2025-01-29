<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'draft' => fake()->boolean(),
            'question' => fake()->text(50).'?',
        ];
    }
}
