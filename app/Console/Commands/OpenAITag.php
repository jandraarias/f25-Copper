<?php

namespace App\Console\Commands;

use League\Csv\Reader;
use League\Csv\Writer;
use OpenAI\Exceptions\TransporterException;
use Illuminate\Console\Command;
use App\Models\Review;
use App\Models\PreferenceOption;

class OpenAITag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai:tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The purpose of this command is to tag each place in the place table with preferences from the preference table';

    /**
     * Execute the console command.
     * 
     * @
     */
    public function handle(\OpenAI\Client $client)
    {
           //Creating a reader to read CSV records and then a list to hold our updated records

           //Currently using a hard-coded location to limit OpenAI token usage and a lack of preventing repeat tagging
           $csvPath = base_path('resources/data/places/Williamsburg_attractions_overview.csv');
           $reader = Reader::createFromPath($csvPath, 'r');
           $reader->setHeaderOffset(0);
           $records = $reader->getRecords();
           $updatedRecords = [];
           $tagArraysforCSV = [];

           //Creates a list of OpenAI tag responses as a list of Strings
           foreach($records as $record) {
              $tagString = $this->tag($client, $record);
              //Outputs to console after each location for debugging
              print $tagString;
              print "\n";
              $tagArraysforCSV[] = $tagString;
              //The API connection seems to be refused a lot more frequently when it happens too frequently so this lowers the amount of connection failures to more reasonable levels
              sleep(5);
           }
           //Go through the records and replace the "review_keywords" column with the Tags from our OpenAI calls
           $iterator = 0;
           foreach ($records as $record) {
              if($iterator < count($tagArraysforCSV)) {
                $record['review_keywords'] = $tagArraysforCSV[$iterator];
              }
              $updatedRecords[] = $record;
              $iterator++;
           }

           //Write the updated records to a new csv
           $writer = Writer::createFromPath(($csvPath), 'w+');
           $header = $reader->getHeader();
           $writer->insertOne($header);
           $writer->insertAll($updatedRecords);

        //$this->line(ltrim($prompt));
        return Command::SUCCESS;
    }
    /**
     * Execute the console command.
     * 
     * @var String
     */
    private function tag(\OpenAI\Client $client, $entry): mixed
    {
        $place = $entry['name'];

        // Get up to 25 reviews
        $reviews = Review::where('place_name', $place)->take(25)->get();

        // If no reviews, return empty tag list
        if ($reviews->isEmpty()) {
            return 'No reviews available';
        }

        // Build strings
        $reviewArray = $reviews->pluck('review_text')->toArray();
        $reviewsString = implode(', ', $reviewArray);

        $tags = PreferenceOption::where('type', 'sub')->get();
        $tagArray = [];

        foreach ($tags as $tag) {
            $tagArray[] = $tag->parent_id == 17
                ? $tag->name . ' Cuisine'
                : $tag->name;
        }

        $tagsString = implode(', ', $tagArray);

        // Handle case where there are fewer than 25 reviews
        $reviewCount = $reviews->count();
        $prompt = "Use the information in these $reviewCount user reviews: $reviewsString\n"
            . "Select between 2–7 of these tags: $tagsString\n"
            . "for this location: $place.\n"
            . "Avoid selecting food-related tags unless food is the primary reason to go to the place. Always select a budget-level, which are 'Free or Low Cost, Budget-Friendly, Moderate, or Luxury'."
            . "Dietary restrictions, accessibility, and budget tags do not count in the 2-7 tag limit. Give me just the tags as a comma-separated list.";

        $messages = [
            ['role' => 'system', 'content' => 'You are a concise tag generator that responds with only a list of tags.'],
            ['role' => 'user', 'content' => $prompt],
        ];

        // --- API Call with retry logic (unchanged) ---
        $attempt = 0;
        do {
            try {
                if ($attempt > 1) {
                    print "Retrying API call in 60 seconds. Attempts: " . $attempt;
                    sleep(60);
                }

                $result = $client->chat()->create([
                    'messages' => $messages,
                    'model' => 'gpt-5-nano',
                ]);

                $reply = trim($result->choices[0]->message->content);
                break;
            } catch (\Exception $e) {
                if ($attempt >= 3) {
                    print "Max Retries reached";
                    throw $e;
                }
                print "Caught Exception: " . $e->getMessage() . "\n";
                $attempt++;
            }
        } while ($attempt < 3);

        return $reply ?: 'No tags generated';
    }
}