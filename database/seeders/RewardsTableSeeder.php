<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str; //for Str::random()
use App\Models\Place;
use App\Models\Reward;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optional: clear existing rewards so does not duplicate
        //Reward::truncate();

        // Types of Discounts 
        $discountOptions = [
            '10% off your visit!',
            '15% off your visit!',
            'Happy Hour Special',
            'Buy One Get One Free Adult Meal',
            'Combo Deal',
            'Free Dessert with Entree',
            'Kids Eat Free',
        ];

        // Get random food-type places
        $places = Place::where('tags', 'LIKE', '%Cuisine%')->inRandomOrder()->take(20)->get();

        foreach($places as $place){
            $discountText = $discountOptions[array_rand($discountOptions)];
            Reward::create([
                'place_id' => $place->id,
                'title' => 'Special Discount at ' . $place->name,
                'description' => $discountText,
                'discount_code' => strtoupper(Str::random(8)),
                'expires_at' => now()->addDays(rand(7,30)),
            ]);
        }
    }
}
