<?php

namespace Database\Factories;

use App\Models\Traveler;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelerFactory extends Factory
{
    protected $model = Traveler::class;

    public function definition(): array
    {
        return [
            // Create a linked user with the traveler role
            'user_id' => User::factory()->create([
                'role' => User::ROLE_TRAVELER,
            ])->id,

            // Only traveler-specific fields
            'bio' => $this->faker->sentence(),
        ];
    }
}
