<?php

namespace Database\Factories;

use App\Models\ItineraryItem;
use App\Models\Itinerary;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraryItemFactory extends Factory
{
    protected $model = ItineraryItem::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+2 weeks', '+2 months');
        $end = (clone $start)->modify('+2 hours');

        return [
            'itinerary_id' => null, // seeder will fill this in
            'type' => $this->faker->randomElement(['Flight', 'Hotel', 'Activity']),
            'title' => $this->faker->sentence(3),
            'start_time' => $start,
            'end_time' => $end,
            'location' => $this->faker->city(),
            'details' => $this->faker->paragraph(),
        ];
    }
}
