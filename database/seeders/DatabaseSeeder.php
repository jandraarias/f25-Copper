<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Database\Factories\TravelerFactory;
use Database\Factories\ItineraryFactory;
use Database\Factories\ItineraryItemFactory;
use Database\Factories\PreferenceProfileFactory;
use Database\Factories\PreferenceFactory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        \App\Models\User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // Travelers
        $travelers = TravelerFactory::new()->count(3)->create();

        foreach ($travelers as $traveler) {
            // Force exactly 2 itineraries per traveler
            $itineraries = ItineraryFactory::new()->count(2)->create([
                'traveler_id' => $traveler->id,
            ]);

            foreach ($itineraries as $itinerary) {
                // Force exactly 3 items per itinerary
                ItineraryItemFactory::new()->count(3)->create([
                    'itinerary_id' => $itinerary->id,
                ]);
            }
        }

        // Preference profiles
        $profiles = PreferenceProfileFactory::new()->count(2)->create();

        foreach ($profiles as $profile) {
            PreferenceFactory::new()->count(3)->create([
                'preference_profile_id' => $profile->id,
            ]);
        }
    }
}
