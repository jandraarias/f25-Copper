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
            'user_id' => User::factory()->create(['role' => User::ROLE_TRAVELER])->id,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'date_of_birth' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber(),
            'bio' => $this->faker->sentence(),
        ];
    }
}
