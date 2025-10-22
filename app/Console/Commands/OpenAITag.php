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
    protected $description = 'The purpose of this command is to tag each place in the place table with preferences from the preference table';

    /**
     * Execute the console command.
     * 
     * @
     */
    public function handle(\OpenAI\Client $client)
    {
           $numLocations = Place::count();
           //$numLocations = 3;
           $tagArraysforCSV = [];

           //Creates a list of OpenAI tag responses as a list of Strings
           for($i = 1; $i < $numLocations + 1; $i++) {
              $tagString = $this->tag($client, $i);
              print $tagString;
              $tagArraysforCSV[] = $tagString;
              //$tagArray = explode(",", $tagString);
           }
           //Creating a reader to read CSV records and then a list to hold our updated records
           $csvPath = storage_path('app/private/seed/places.csv');
           $reader = Reader::createFromPath($csvPath, 'r');
           $reader->setHeaderOffset(0);
           $records = $reader->getRecords();
           $updatedRecords = [];

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
           $writer = Writer::createFromPath(storage_path('app/private/seed/places_update.csv'), 'w+');
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
        //Get the place given by the $entry variable(Could be any field, but currently is the place_id)
        $place = Place::find($entry);
        //Grab the first x listed reviews
        $reviews = Review::where('place_id', $entry)->take(25)->get();
        //Grab all the preferences that are not preference catagories
        $tags = PreferenceOption::where('type', 'sub')->get();

        $reviewArray = [];
        $tagArray = [];

        //Grab the review text from the review table entries into a list
        foreach($reviews as $review) {
            $reviewArray[] = $review->text;
        }

        //Grab the preference names from the preference table entries into a list
        foreach($tags as $tag) {
            $tagArray[] = $tag->name;
        }

        //Change the arrays into Strings seperating each entry with a comma
        $reviewsString = implode(', ', $reviewArray);
        $tagsString = implode(', ', $tagArray);

        //Prompt placed to OpenAI
        $prompt = 'Using these 10 user reviews ' . $reviewsString . "\n To select between 1-5 of these tags " . $tagsString . "\n for this location " 
        . $place->name . ".\n Give me just the tags as a comma seperated list";

        //OpenAI role assignments
        $messages = [
            ['role' => 'system', 'content' => 'You are helpful assistant'],
            ['role' => 'user', 'content' => $prompt],
        ];

        //Openai chat attributes and model selection
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