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
            'Cultural & Historical' => ['Cultural and Historical Sites', 'Museums', 'Art Galleries', 'Festivals','Local Traditions & Workshops'],
            'Relaxation & Wellness' => ['Spa', 'Wellness Center', 'Meditation and Yoga', 'Hot Springs'],
            'Food & Drink' => ['Local Cuisine', 'Fine Dining', 'Street Food', 'Coffee Shops', 'Breweries, Distilleries, Wineries'],
            'Nature & Adventure' => ['Outdoor Activities', 'Beaches', 'Scenic Sports', 'National Parks & Trails' , 'Theme Parks', 'Zoos', 'Aquariums', 'Interactive Museums'],
            'Entertainment & Nightlife' => ['Live Music & Concerts', 'Nightclubs', 'Comedy', 'Theatre & Shows', 'Local Performances','Casino'],
            'Shopping & Lifestyle' => ['Local Markets', 'Malls & Outlets', 'Boutiques & Fashion', ' Artisan Shops'],
            'Sports & Fitness' => ['Fitness Centers', 'Fitness Events', 'Local Sporting Events'],
            'Other' => ['Family-Friendly', 'Senior Friendly', 'Pet Friendly', 'Wheelchair Accessible', 'Visual/Hearing Impaired']
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
