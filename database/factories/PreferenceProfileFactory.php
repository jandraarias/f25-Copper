<?php

namespace Database\Factories;

use App\Models\PreferenceProfile;
use App\Models\Traveler;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceProfileFactory extends Factory
{
    protected $model = PreferenceProfile::class;

    public function definition(): array
    {
        return [
            'traveler_id' => Traveler::query()->inRandomOrder()->value('id') ?? Traveler::factory(),
            'name' => $this->faker->word(),
        ];
    }
}
