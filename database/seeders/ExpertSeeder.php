<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Expert;
use App\Models\ExpertReview;

class ExpertSeeder extends Seeder
{
    public function run()
    {
        $experts = [
            [
                'name' => 'Aiko Tanaka',
                'city' => 'Williamsburg',
                'photo_url' => 'https://i.pravatar.cc/300?img=5',
                'bio'       => '
                        Im passionate about guiding people through Williamsburg’s rich food scene and cultural heritage.  
                        I love introducing guests to hidden eateries, bustling markets, and the stories behind local traditions. 
                        Fluent in English and Spanish, I make sure everyone feels welcome and leaves with both a full stomach and a deeper appreciation of the city’s culture.
',
                'availability' => 'Weekends',
                'languages_spoken' => 'English, Spanish',
                'years_of_experience' => 10,
                'hourly_rate' => 75.00,
                'expertise' => 'Food and Culture',


            ],
            [
                'name' => 'Luis Martínez',
                'city' => 'Williamsburg',
                'photo_url' => 'https://i.pravatar.cc/300?img=12',
                'bio' => '
                    For the past 8 years, I’ve been sharing Williamsburg’s vibrant nightlife, architecture, and art with visitors. 
                    I specialize in evening tours that highlight the city’s creative pulse—whether it’s exploring galleries, admiring iconic landmarks, or finding the best spots for music and cocktails. 
                    I love creating experiences that are stylish, insightful, and full of energy.',
                'expertise' => 'Nightlife and Art',
                'availability' => 'Evenings',
                'languages_spoken' => 'English, French',
                'years_of_experience' => 8,
                'hourly_rate' => 65.00,
                

            ],
            [
                'name' => 'Sophie Laurent',
                'city' => 'Williamsburg',
                'photo_url' => 'https://i.pravatar.cc/300?img=28',
                'bio' => '
                             Canguide guests through Williamsburg’s fashion scene, museums, and cultural history. 
                            I’m fluent in English, French, and Italian, which allows me to connect with travelers from around the world. 
                            Whether we’re exploring historic exhibits or discovering the city’s fashion-forward side, I make sure every tour blends elegance, storytelling, and unforgettable experiences.
',
                'availability' => 'Weekdays',
                'languages_spoken' => 'English, French, Italian',
                'years_of_experience' => 12,
                'hourly_rate' => 80.00,
                'expertise' => 'Fashion and Museums',
            ],
        ];

        foreach ($experts as $data) {

            // Create user account
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $this->makeEmail($data['name']),
                'password'      => Hash::make('password'),
                'role'          => User::ROLE_EXPERT,
                'phone_number'  => null,
                'date_of_birth' => now()->subYears(rand(25, 45))->format('Y-m-d'),
            ]);

            // Create expert profile
            $expert = Expert::create([
                'user_id'            => $user->id,
                'name'               => $data['name'],
                'city'               => $data['city'],

                // Store URL inside profile_photo_path
                'profile_photo_path' => $data['photo_url'],

                'bio'                => $data['bio'],
                'availability'       => $data['availability'],
                'languages'         => $data['languages_spoken'],
                'experience_years'   => $data['years_of_experience'],
                'hourly_rate'        => $data['hourly_rate'],
                'expertise'          => $data['expertise'],

            ]);

            // Generate reviews
            ExpertReview::factory()
                ->count(rand(3, 15))
                ->create([
                    'expert_id' => $expert->id,
                ]);
        }
    }

    protected function makeEmail(string $name): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '.', $name));
        return "{$slug}@" . config('app.domain', 'example.com');
    }
}
