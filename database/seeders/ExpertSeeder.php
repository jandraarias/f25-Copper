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
                'bio' => 'Local food and culture expert with 10+ years of guiding.',
            ],
            [
                'name' => 'Luis Martínez',
                'city' => 'Williamsburg',
                'photo_url' => 'https://i.pravatar.cc/300?img=12',
                'bio' => 'Nightlife, architecture, and art tour specialist.',
            ],
            [
                'name' => 'Sophie Laurent',
                'city' => 'Williamsburg',
                'photo_url' => 'https://i.pravatar.cc/300?img=28',
                'bio' => 'Fashion, museums, and cultural history expert.',
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
