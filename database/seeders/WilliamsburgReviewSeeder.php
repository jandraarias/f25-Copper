<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WilliamsburgReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Correct path for your CSV file (based on your screenshot)
        $path = storage_path('app/data/reviews/reviews.csv');

        if (!file_exists($path)) {
            $this->command->error("File not found at: $path");
            return;
        }

        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $map = array_flip($header);

        $count = 0;
        while (($row = fgetcsv($fh)) !== false) {
            \App\Models\Review::updateOrCreate(
                ['review_id' => $row[$map['review_id']]],
                [
                    'place_id'        => $row[$map['place_id']],
                    'place_name'      => $row[$map['place_name']],
                    'reviewer_name'   => $row[$map['reviewer_name']],
                    'rating'          => $row[$map['rating']],
                    'review_text'     => $row[$map['review_text']],
                    'reviewed_at'     => $row[$map['reviewed_at']],
                    'review_keywords' => $row[$map['review_keywords']],
                ]
            );
            $count++;
        }

        fclose($fh);

        $this->command->info("Imported {$count} Williamsburg reviews successfully.");
    }
}