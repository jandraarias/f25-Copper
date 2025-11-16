<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpertReviewFactory extends Factory
{
    public function definition()
    {
        return [
            'rating' => rand(3, 5),
            'comment' => $this->faker->sentence(),
        ];
    }
}
