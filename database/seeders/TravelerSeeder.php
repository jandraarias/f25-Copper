<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Traveler;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\PreferenceProfile;
use App\Models\Preference;

class TravelerSeeder extends Seeder
{
    public function run(): void
    {
        $travelers = [
            [
                'name'      => 'Emma Carter',
                'photo_url' => 'https://i.pravatar.cc/300?img=32',
                'bio'       => 'Avid traveler who loves nature, coffee shops, and boutique hotels.',
            ],
            [
                'name'      => 'Jackson Lee',
                'photo_url' => 'https://i.pravatar.cc/300?img=15',
                'bio'       => 'Adventure seeker exploring the world one city at a time.',
            ],
            [
                'name'      => 'Maya Singh',
                'photo_url' => 'https://i.pravatar.cc/300?img=48',
                'bio'       => 'Culture lover, food explorer, and part-time photographer.',
            ],
        ];

        foreach ($travelers as $data) {

            // --- Create user ---
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $this->makeEmail($data['name']),
                'password'      => Hash::make('password'),
                'role'          => User::ROLE_TRAVELER,
                'phone_number'  => null,
                'date_of_birth' => now()->subYears(rand(20, 40))->format('Y-m-d'),
            ]);

            // --- Create traveler profile ---
            $traveler = Traveler::create([
                'user_id'            => $user->id,
                'bio'                => $data['bio'],
                'profile_photo_path' => $data['photo_url'], // remote seeded URL
            ]);

            // --- Create 2 itineraries per traveler ---
            $itineraries = Itinerary::factory()->count(2)->create([
                'traveler_id' => $traveler->id,
            ]);

            foreach ($itineraries as $itinerary) {

                // Each itinerary gets 3 items
                ItineraryItem::factory()->count(3)->create([
                    'itinerary_id' => $itinerary->id,
                ]);

                // Attach a random country
                $country = \App\Models\Country::inRandomOrder()->first();
                if ($country) {
                    $itinerary->countries()->attach($country->id);
                }
            }

            // --- Create preferences ---
            $profile = PreferenceProfile::factory()->create([
                'traveler_id' => $traveler->id,
            ]);

            Preference::factory()->count(3)->create([
                'preference_profile_id' => $profile->id,
            ]);
        }
    }

    /**
     * Generate email from name.
     */
    protected function makeEmail(string $name): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '.', $name));
        return "{$slug}@" . config('app.domain', 'example.com');
    }
}
