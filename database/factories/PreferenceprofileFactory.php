<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Preferenceprofile;
use App\Models\Traveler;

class PreferenceprofileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preferenceprofile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'traveler_id' => Traveler::factory(),
        ];
    }
}
