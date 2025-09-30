<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Preference;

class PreferencesTableSeeder extends Seeder
{
    public function run(): void
    {
        //  Define main interests and sub-interests
        $data = [
            'Cultural & Historical' => ['Landmarks', 'Museums', 'Historical Sites'],
            'Relaxation' => ['Spas', 'Beaches', 'Resorts'],
            'Food & Drink' => ['Restaurants', 'Street Food', 'Breweries'],
            'Family-Friendly' => ['Theme Parks', 'Zoos', 'Aquariums'],
            'Nature & Wildlife' => ['National Parks', 'Wildlife Safaris', 'Botanical Gardens'],
            'Entertainment' => ['Concerts', 'Movies', 'Theatre'],
            'Nightlife' => ['Bars', 'Clubs', 'Live Music'],
            'Social' => ['Meetups', 'Events', 'Volunteering'],
            'Extreme Sports' => ['Skydiving', 'Bungee Jumping', 'Surfing'],
            'Hiking & Adventure' => ['Trails', 'Camping', 'Climbing'],
            'Community-Based' => ['Local Tours', 'Homestays', 'Cultural Exchanges'],
        ];

        foreach ($data as $main => $subs) {
            // Create main interest
            $mainPref = Preference::create([
                'name' => $main,
                'type' => 'main',
                'category' => 'interest', 
                'parent_id' => null,
            ]);

            foreach ($subs as $sub) {
                // Create sub-interest
                Preference::create([
                    'name' => $sub,
                    'type' => 'sub',
                    'category' => 'interest', // same or different as needed
                    'parent_id' => $mainPref->id,
                ]);
            }
        }
    }
}
