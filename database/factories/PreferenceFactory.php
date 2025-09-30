<?php

namespace Database\Factories;

use App\Models\Preference;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{
    protected $model = Preference::class;

    public function definition(): array
    {
        return [
            'preference_profile_id' => null, // seeder will override
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->word(),
        ];
    }
}
