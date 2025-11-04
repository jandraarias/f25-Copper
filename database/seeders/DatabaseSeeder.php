<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Traveler;
use Database\Factories\ItineraryFactory;
use Database\Factories\ItineraryItemFactory;
use Database\Factories\PreferenceProfileFactory;
use Database\Factories\PreferenceFactory;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed the  preference options
        $this->call(PreferencesTableSeeder::class);

        // Seed the countries first
        $this->call(CountrySeeder::class);

        // Seed the Rewards Table
        $this->call(RewardsTableSeeder::class);

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
        $users = User::factory()
            ->count(3)
            ->create(['role' => User::ROLE_TRAVELER]);

        foreach ($users as $user) {
            // Each traveler profile belongs to a user
            $traveler = Traveler::create([
                'user_id' => $user->id,
                'bio' => fake()->sentence(),
            ]);

            // Each Traveler gets 2 itineraries
            $itineraries = ItineraryFactory::new()->count(2)->create([
                'traveler_id' => $traveler->id,
            ]);

            foreach ($itineraries as $itinerary) {
                // Each Itinerary gets 3 items
                ItineraryItemFactory::new()->count(3)->create([
                    'itinerary_id' => $itinerary->id,
                ]);

                // Attach a random country to each itinerary
                $country = \App\Models\Country::inRandomOrder()->first();
                if ($country) {
                    $itinerary->countries()->attach($country->id);
                }
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
