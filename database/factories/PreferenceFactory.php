<?php

namespace Database\Factories;

use App\Models\Preference;
use App\Models\PreferenceProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{
    protected $model = Preference::class;

    public function definition(): array
    {
        return [
            'preference_profile_id' => PreferenceProfile::factory(),
            'key' => $this->faker->word(),
            'value' => $this->faker->word(),
        ];
    }
}
