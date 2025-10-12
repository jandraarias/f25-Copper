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
                'Local Traditions & Workshops'
            ],
            'Relaxation & Wellness' => [
                'Spa',
                'Wellness Center',
                'Meditation and Yoga',
                'Hot Springs'
            ],
            'Food & Drink' => [
                'Local Cuisine',
                'Fine Dining',
                'Street Food',
                'Coffee Shops',
                'Breweries, Distilleries, Wineries',
                // Sub-sub group for dietary preferences
                'Dietary Preferences' => [
                    'Gluten Free',
                    'Dairy Free',
                    'Nut-Free',
                    'Shellfish-Free',
                    'Egg-Free',
                    'Soy-Free',
                    'Fish-Free',
                    'Sesame-Free'
                ]
            ],
            'Nature & Adventure' => [
                'Outdoor Activities',
                'Beaches',
                'Scenic Spots',
                'National Parks & Trails',
                'Theme Parks',
                'Zoos',
                'Aquariums',
                'Interactive Museums'
            ],
            'Entertainment & Nightlife' => [
                'Live Music & Concerts',
                'Nightclubs',
                'Comedy',
                'Theatre & Shows',
                'Local Performances',
                'Casino'
            ],
            'Shopping & Lifestyle' => [
                'Local Markets',
                'Malls & Outlets',
                'Boutiques & Fashion',
                'Artisan Shops'
            ],
            'Sports & Fitness' => [
                'Fitness Centers',
                'Fitness Events',
                'Local Sporting Events'
            ],
            'Other' => [
                'Family-Friendly',
                'Senior Friendly',
                'Pet Friendly',
                'Wheelchair Accessible',
                'Visual/Hearing Impaired'
            ]
        ];

        foreach ($data as $main => $subs) {
            $mainOption = PreferenceOption::create([
                'name' => $main,
                'type' => 'main',
                'parent_id' => null,
            ]);

            foreach ($subs as $key => $sub) {
                if (is_array($sub)) {
                    // This handles sub-sub groups, e.g. 'Dietary Preferences' => ['Gluten Free', ...]
                    // This structure is needed to represent categories with further nested options.
                    $subOption = PreferenceOption::create([
                        'name' => $key,
                        'type' => 'sub',
                        'parent_id' => $mainOption->id,
                    ]);
                    foreach ($sub as $subSub) {
                        PreferenceOption::create([
                            'name' => $subSub,
                            'type' => 'sub-sub',
                            'parent_id' => $subOption->id,
                        ]);
                    }
                } else {
                    // Normal sub option
                    PreferenceOption::create([
                        'name' => $sub,
                        'type' => 'sub',
                        'parent_id' => $mainOption->id,
                    ]);
                }
            }
        }
    }
}
