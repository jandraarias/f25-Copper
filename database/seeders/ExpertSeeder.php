<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            $expert = Expert::create($data);

            // Add some fake reviews
            ExpertReview::factory()->count(rand(3, 15))->create([
                'expert_id' => $expert->id,
            ]);
        }
    }
}
