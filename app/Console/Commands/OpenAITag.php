<?php

namespace App\Console\Commands;

use League\Csv\Reader;
use League\Csv\Writer;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Exceptions;
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
           $csvPath = storage_path('app\private\places\Williamsburg_food_overview.csv');
           $reader = Reader::createFromPath($csvPath, 'r');
           $reader->setHeaderOffset(0);
           $records = $reader->getRecords();
           $numLocations = iterator_count($records);
           //$numLocations = 3;
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
           $writer = Writer::createFromPath(storage_path('app\private\places\Williamsburg_food_overview.csv'), 'w+');
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
        //Get the place given by the $entry
        $place = $entry['name'];
        //Grab the first x listed reviews
        $reviews = Review::where('place_name', $place)->take(25)->get();
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
            //Add Cuisine to the end of the Cuisine preferences so the AI uses them correctly
            if($tag->parent_id == 17) {
                $tagArray[] = $tag->name . " Cuisine";
                continue;
            }
            $tagArray[] = $tag->name;
        }

        //Change the arrays into Strings seperating each entry with a comma
        $reviewsString = implode(', ', $reviewArray);
        $tagsString = implode(', ', $tagArray);

        //Prompt placed to OpenAI
        $prompt = 'Use the information in these 25 user reviews ' . $reviewsString . "\n To select between 1-5 of these tags " . $tagsString . "\n for this location " 
        . $place . ".\n Only select a cusine if food is the primary reason to go to the place. Never select more than 5 tags. Give me just the tags as a comma seperated list";

        //OpenAI role assignments
        $messages = [
            ['role' => 'system', 'content' => 'You are helpful assistant'],
            ['role' => 'user', 'content' => $prompt],
        ];

        //The API call is in a try catch block because sometimes the connection fails. It seems related to frequency but it is nowhere near the rate limits so I am not sure why.
        $attempt = 0;
        do {
            try {
                if($attempt > 1) {
                print "Retrying API call in 60 seconds. Attempts: " . $attempt;
                sleep(60);
                }
                //OpenAI Chat Completion
                $result = $client->chat()->create([
                'messages' => $messages,
                'model' => 'gpt-5-nano',
            ]);
            $reply = $result->choices[0]->message->content;
            break;
            }
            catch(TransporterException $e) {
                if ($attempt >= 3) {
                    print "Max Retries reached";
                    throw $e;
                }
                print "Caught TransporterException: " . $e->getMessage() . "\n";
                $attempt++;
            }
            catch(\Exception $e) {
                if ($attempt >= 3) {
                    print "Max Retries reached";
                    throw $e;
                }
                print "Caught Exception: " . $e->getMessage() . "\n";
                $attempt++;
            }
        } while ($attempt < 3);
        
        //$reply = $result->choices[0]->message->content;
        return $reply;
    }
}