<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Business;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businesses = [
            [
                'name'        => 'Sunrise Coffee Roasters',
                'city'        => 'Williamsburg',
                'photo_url'   => 'https://i.pravatar.cc/300?img=60',
                'website'     => 'https://sunrisecoffee.example.com',
                'description' => 'Locally-owned specialty coffee shop with organic blends and cozy seating.',
            ],
            [
                'name'        => 'Urban Trails Outfitters',
                'city'        => 'Williamsburg',
                'photo_url'   => 'https://i.pravatar.cc/300?img=62',
                'website'     => 'https://urbantrails.example.com',
                'description' => 'Outdoor gear retailer offering hiking equipment and guided trail sessions.',
            ],
            [
                'name'        => 'Harbor View Bistro',
                'city'        => 'Williamsburg',
                'photo_url'   => 'https://i.pravatar.cc/300?img=64',
                'website'     => 'https://harborviewbistro.example.com',
                'description' => 'European-inspired restaurant known for waterfront dining and seasonal menus.',
            ],
        ];

        foreach ($businesses as $data) {

            // Create user
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $this->makeEmail($data['name']),
                'password'      => Hash::make('password'),
                'role'          => User::ROLE_BUSINESS,
                'phone_number'  => null,
                'date_of_birth' => null, // not needed for businesses
            ]);

            // Create business profile
            Business::create([
                'user_id'            => $user->id,
                'name'               => $data['name'],
                'city'               => $data['city'],
                'website'            => $data['website'],
                'description'        => $data['description'],
                'profile_photo_path' => $data['photo_url'], // remote seeded image
            ]);
        }
    }

    /**
     * Generate email based on name.
     */
    protected function makeEmail(string $name): string
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '.', $name));
        return "{$slug}@" . config('app.domain', 'example.com');
    }
}
