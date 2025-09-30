<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Traveler;
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
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Travelers with linked Users
        $travelers = TravelerFactory::new()->count(3)->create();

        foreach ($travelers as $traveler) {
            // Ensure traveler has a user
            $user = $traveler->user()->updateOrCreate(
                ['email' => $traveler->email],
                [
                    'name' => $traveler->name,
                    'password' => Hash::make('password'), // default password
                    'role' => User::ROLE_TRAVELER,
                ]
            );

            $traveler->update(['user_id' => $user->id]);

            // Each Traveler gets 2 itineraries
            $itineraries = ItineraryFactory::new()->count(2)->create([
                'traveler_id' => $traveler->id,
            ]);

            foreach ($itineraries as $itinerary) {
                // Each Itinerary gets 3 items
                ItineraryItemFactory::new()->count(3)->create([
                    'itinerary_id' => $itinerary->id,
                ]);
            }

            // Each Traveler gets 1 preference profile
            $profile = PreferenceProfileFactory::new()->create([
                'traveler_id' => $traveler->id,
            ]);

            // Each profile gets 3 preferences
            PreferenceFactory::new()->count(3)->create([
                'preference_profile_id' => $profile->id,
            ]);
        }
    }
}
