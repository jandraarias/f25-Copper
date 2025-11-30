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
            'Meal Type' => [
                'Breakfast', 'Brunch', 'Lunch',
                'Dinner',
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
            'Entertainmnet & Performing Arts' => [
                'Live Music & Concerts',
                'Comedy',
                'Theater & Shows',
                'Local Performances',
            ],
            'Nightlife' => [
                'Nightclubs',
                'Casino',
                'Bars & Pubs',
            ],
            'Indoor Fun' => [
                'Arcades',
                'Game Center',
                'Laser Tag',
                'Trampoline Parks',
                'Escape Rooms',
                'Bowling Alleys',
                'Mini Golf',
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
            'Accessibility' => [
                'Wheelchair Accessible',
                'Visual/Hearing Impaired',
            ],
            'Visitor Type' => [
                'Family-Friendly',
                'Child-Friendly',
                'Senior Friendly',
                'Pet Friendly',
                'All-Ages',
                'Couple-Friendly',
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
            'Budget & Price Level' => [
                'Free or Low Cost ',
                'Budget-Friendly',
                'Moderate',
                'Luxury',
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
