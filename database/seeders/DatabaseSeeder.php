<?php

namespace Database\Seeders;

use App\Models\Traveler;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user for Filament login
        \App\Models\User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // change in production
            ]
        );

        // Seed a few Travelers
        $travelers = Traveler::factory()->count(3)->create([
            // override if your factory doesn’t have email/name
        ]);

        $travelers->each(function (Traveler $traveler) {
            // Each Traveler gets 1–2 Itineraries
            $itineraries = Itinerary::factory()->count(rand(1, 2))->create([
                'traveler_id' => $traveler->id,
            ]);

            $itineraries->each(function (Itinerary $itinerary) {
                // Each Itinerary gets 2–4 Itinerary Items
                ItineraryItem::factory()->count(rand(2, 4))->create([
                    'itinerary_id' => $itinerary->id,
                ]);
            });
        });
    }
}
