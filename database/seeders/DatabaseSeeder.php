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
        // Admin
        User::query()->updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        // Traveler seeder
        $this->call(TravelerSeeder::class);

        // Expert seeder
        $this->call(ExpertSeeder::class);

        // Preference options
        $this->call(PreferencesTableSeeder::class);

        // Countries
        $this->call(CountrySeeder::class);

        // Rewards
        $this->call(RewardsTableSeeder::class);
    }
}
