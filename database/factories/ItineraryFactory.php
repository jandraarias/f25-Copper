<?php

namespace Database\Factories;

use App\Models\Itinerary;
use App\Models\Traveler;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraryFactory extends Factory
{
    protected $model = Itinerary::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 week', '+1 month');
        $end = (clone $start)->modify('+1 week');

        return [
            'traveler_id' => null, // seeder will fill this in
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(12),
            'start_date' => $start,
            'end_date' => $end,
            'country' => $this->faker->country(),
            'location' => $this->faker->city(),
        ];
    }
}
