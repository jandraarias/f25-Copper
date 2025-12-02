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
        $expertData = [
            [
                'name'      => 'Aiko Tanaka',
                'city'      => 'Williamsburg',
                'profile_photo_path' => 'defaults/expert.png',
                'bio'       => 'Local food and culture expert with 10+ years of guiding.',
            ],
            [
                'name'      => 'Luis Martínez',
                'city'      => 'Williamsburg',
                'profile_photo_path' => 'defaults/expert.png',
                'bio'       => 'Nightlife, architecture, and art tour specialist.',
            ],
            [
                'name'      => 'Sophie Laurent',
                'city'      => 'Williamsburg',
                'profile_photo_path' => 'defaults/expert.png',
                'bio'       => 'Fashion, museums, and cultural history expert.',
            ],
        ];

        foreach ($expertData as $data) {

            // Create a user account for the expert
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $this->makeEmail($data['name']),
                'password'      => Hash::make('password'),
                'role'          => User::ROLE_EXPERT,
                'phone_number'  => null,
                'date_of_birth' => now()->subYears(rand(25, 45))->format('Y-m-d'),
            ]);

            // Create Expert profile
            $expert = Expert::create([
                'user_id'            => $user->id,
                'name'               => $data['name'],
                'city'               => $data['city'],
                'profile_photo_path' => $data['profile_photo_path'],
                'bio'                => $data['bio'],
            ]);

            // Add review records
            ExpertReview::factory()
                ->count(rand(3, 15))
                ->create([
                    'expert_id' => $expert->id,
                ]);
        }
    }

    /**
     * Generate a safe, unique email for seeded users.
     */
    protected function makeEmail(string $name): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '.', $name));
        return "{$slug}@" . config('app.domain', 'example.com');
    }
}
