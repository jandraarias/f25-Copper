<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('preferences')->insert([
        ['name' => 'Music', 'type' => 'Interest', 'category' => 'Entertainment'],
        ['name' => 'Travel', 'type' => 'Interest', 'category' => 'Lifestyle'],
        ['name' => 'Technology', 'type' => 'Interest', 'category' => 'Education'],
        ['name' => 'Fitness', 'type' => 'Activity', 'category' => 'Health'],
        ['name' => 'Cooking', 'type' => 'Hobby', 'category' => 'Lifestyle'],
        ['name' => 'Reading', 'type' => 'Hobby', 'category' => 'Education'],
    ]);
    }
}
