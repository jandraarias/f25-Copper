<?php

namespace App\Console\Commands;

use League\Csv\Reader;
use League\Csv\Writer;
use OpenAI\Exceptions\TransporterException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Review;

class OpenAiDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai:description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The purpose of this command is to give descriptions to places that do not have one';

    /**
     * Execute the console command.
     */
    public function handle(\OpenAI\Client $client)
    {
         //Creating a reader to read CSV records and then a list to hold our updated records
        $csvPath = storage_path('app\private\places\Williamsburg_attractions_overview.csv');
        $reader = Reader::createFromPath($csvPath, 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $numLocations = iterator_count($records);
        $updatedRecords = [];
        $descriptionArraysforCSV = [];

        //Check each record for a description and make a call to our openai description function if there isn't one
        foreach($records as $record) {
            $descriptionString = $record['description'];
            //If the description is too short or empty we call our AI function to make a new description
            if(Str::length($descriptionString) < 20 || $descriptionString == null) {
                $descriptionString = $this->description($client, $record);
                //Outputs to console after each location for debugging
                print $descriptionString;
                print "\n";
            }
            $descriptionArraysforCSV[] = $descriptionString;
            //The API connection seems to be refused a lot more frequently when it happens too frequently so this lowers the amount of connection failures to more reasonable levels
            sleep(5);
        }

        //Go through the records and replace the "description" column with the description from our OpenAI calls
        $iterator = 0;
        foreach ($records as $record) {
            if($iterator < count($descriptionArraysforCSV)) {
            $record['description'] = $descriptionArraysforCSV[$iterator];
            }
            $updatedRecords[] = $record;
            $iterator++;
        }

        //Write the updated records to a new csv
        $writer = Writer::createFromPath(($csvPath), 'w+');
        $header = $reader->getHeader();
        $writer->insertOne($header);
        $writer->insertAll($updatedRecords);

        return Command::SUCCESS;
    }

    private function description(\OpenAI\Client $client, $entry): mixed
    {
        //Get the place given by the $entry
        $place = $entry['name'];
        //Grab the first x listed reviews
        $reviews = Review::where('place_name', $place)->take(25)->get();

        $reviewArray = [];

        //Grab the review text from the review table entries into a list
        foreach($reviews as $review) {
            $reviewArray[] = $review->text;
        }

        //Change the arrays into Strings seperating each entry with a comma
        $reviewsString = implode(', ', $reviewArray);

        //Prompt placed to OpenAI
        $prompt = 'Use the information in these 25 user reviews ' . $reviewsString . "\n To generate a description for this location " . $place . 
                  "\n Keep the description to a short paragraph of no more than 5 sentences.";

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
