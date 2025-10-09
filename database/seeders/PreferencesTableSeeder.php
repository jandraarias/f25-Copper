<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreferenceOption;

class PreferencesTableSeeder extends Seeder
{
    public function run(): void
    {
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
            $mainOption = PreferenceOption::create([
                'name' => $main,
                'type' => 'main',
                'parent_id' => null,
            ]);

            foreach ($subs as $sub) {
                PreferenceOption::create([
                    'name' => $sub,
                    'type' => 'sub',
                    'parent_id' => $mainOption->id,
                ]);
            }
        }
    }
}
