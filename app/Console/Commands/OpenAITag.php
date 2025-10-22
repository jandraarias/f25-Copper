<?php

namespace App\Console\Commands;

use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Console\Command;
use App\Models\Place;
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * 
     * @
     */
    public function handle(\OpenAI\Client $client)
    {
           //$numLocations = Place::count() + 1;
           $numLocations = 4;
           $tagArraysforCSV = [];

           for($i = 1; $i < $numLocations; $i++) {
              $tagString = $this->tag($client, $i);
              print $tagString;
              $tagArraysforCSV[] = $tagString;
              //$tagArray = explode(",", $tagString);
           }
           $csvPath = storage_path('app/private/seed/places.csv');
           $reader = Reader::createFromPath($csvPath, 'r');
           $reader->setHeaderOffset(0);
           $records = $reader->getRecords();
           $updatedRecords = [];
           $iterator = 0;
           foreach ($records as $record) {
              if($iterator < count($tagArraysforCSV)) {
                $record['review_keywords'] = $tagArraysforCSV[$iterator];
              }
              $updatedRecords[] = $record;
              $iterator++;
           }
           $writer = Writer::createFromPath(storage_path('app/private/seed/places_update.csv'), 'w+');
           $header = $reader->getHeader();
           $writer->insertOne($header);
           $writer->insertAll($updatedRecords);

        //$this->line(ltrim($prompt));
        return Command::SUCCESS;
    }
    private function tag(\OpenAI\Client $client, $entry): mixed
    {
        $place = Place::find($entry);
        $reviews = Review::where('place_id', $entry)->take(25)->get();
        $tags = PreferenceOption::where('type', 'sub')->get();

        $reviewArray = [];
        $tagArray = [];

        foreach($reviews as $review) {
            $reviewArray[] = $review->text;
        }

        foreach($tags as $tag) {
            $tagArray[] = $tag->name;
        }

        $reviewsString = implode(', ', $reviewArray);
        $tagsString = implode(', ', $tagArray);

        $prompt = 'Using these 10 user reviews ' . $reviewsString . "\n To select between 1-5 of these tags " . $tagsString . "\n for this location " 
        . $place->name . ".\n Give me just the tags as a comma seperated list";

        
        $messages = [
            ['role' => 'system', 'content' => 'You are helpful assistant'],
            ['role' => 'user', 'content' => $prompt],
        ];

        $result = $client->chat()->create([
            'messages' => $messages,
            'model' => 'gpt-5-nano',
        ]);
        
        //$this->line(ltrim($result->choices[0]->message->content));
        $reply = $result->choices[0]->message->content;
        return $reply;
        //return Command::SUCCESS;
    }
}