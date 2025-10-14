<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreferenceOption;

class PreferencesTableSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Cultural & Historical' => [
                'Cultural and Historical Sites',
                'Museums',
                'Art Galleries',
                'Festivals',
                'Local Traditions & Workshops',
            ],
            'Relaxation & Wellness' => [
                'Spa',
                'Wellness Center',
                'Meditation and Yoga',
                'Hot Springs',
            ],
            'Food & Drink' => [
                'Local Cuisine',
                'Fine Dining',
                'Street Food',
                'Coffee Shops',
                'Breweries, Distilleries, Wineries',
            ],
             'Cuisine' => [
                'American',
                'Italian',
                'Korean',
                'Chinese',
                'Mexican',
                'Japanese',
                'Indian',
                'French',
                'Thai',
                'Mediterranean',
                'Vietnamese',
                'Greek',
            ],
            'Nature & Adventure' => [
                'Outdoor Activities',
                'Beaches',
                'Scenic Spots',
                'National Parks & Trails',
                'Theme Parks',
                'Zoos',
                'Aquariums',
                'Interactive Museums',
            ],
            'Entertainment & Nightlife' => [
                'Live Music & Concerts',
                'Nightclubs',
                'Comedy',
                'Theatre & Shows',
                'Local Performances',
                'Casino',
            ],
            'Shopping & Lifestyle' => [
                'Local Markets',
                'Malls & Outlets',
                'Boutiques & Fashion',
                'Artisan Shops',
            ],
            'Sports & Fitness' => [
                'Fitness Centers',
                'Fitness Events',
                'Local Sporting Events',
            ],
            'Other' => [
                'Family-Friendly',
                'Senior Friendly',
                'Pet Friendly',
                'Wheelchair Accessible',
                'Visual/Hearing Impaired',
            ],
            'Dietary Restrictions' => [
                'Gluten Free',
                'Vegan',
                'Vegetarian',
                'Dairy Free',
                'Nut-Free',
                'Shellfish-Free',
                'Egg-Free',
                'Soy-Free',
                'Fish-Free',
                'Sesame-Free',
            ],
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
