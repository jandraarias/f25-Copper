<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use App\Models\Place;
use App\Models\Review;
use Carbon\Carbon;
use CallbackFilterIterator;

class ImportPlaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $placeDir = storage_path('app/private/places');
        $reviewDir = storage_path('app/private/reviews');

        $placeFiles = scandir($placeDir);
        $reviewFiles = scandir($reviewDir);
        
        foreach ($placeFiles as $placeFile) {
            if($placeFile == '.' || $placeFile == '..') {
                continue;
            }
            $placePath = $placeDir . "/" . $placeFile;
            $placeRecords = $this->importCSV($placePath);
            $reviewRecords = [];
            //Get the matching review file
            foreach($reviewFiles as $reviewFile) {
                if($reviewFile == '.' || $reviewFile == '..') {
                continue;
                }
                $fileNameMatch = $this->compareFileNames($placeFile, $reviewFile);
                if($fileNameMatch == true) {
                    $reviewPath = $reviewDir . "/" . $reviewFile;
                    $reviewRecords = $this->importCSV($reviewPath);
                    break;
                }
            }
            $trimmedPlaceRecords = $this->trimPlaces($placeRecords, $reviewRecords);
            $trimmedReviewRecords = $this->trimReviews($placeRecords, $trimmedPlaceRecords, $reviewRecords);
            
            $this->placesDataToTable($trimmedPlaceRecords);
            print "Contents of " . $placeFile . " sucessfully uploaded to database\n";
            $this->reviewsDataToTable($trimmedReviewRecords);
            print "Contents of " . $reviewFile . " sucessfully uploaded to database\n";

        }
    }

    /**
     * Turn the data from the csv into a list.
     */
    private function importCSV($csvPath) {
           $records = [];
           $reader = Reader::createFromPath($csvPath, 'r');
           $reader->setHeaderOffset(0);
           $records = $reader->getRecords();
           return $records;
    }

    /**
     * import the places csv data from a list into the places table.
     */
    private function placesDataToTable($records) {
        foreach ($records as $record) {
            //Check if table entry already exists by searching the name
            $attributes = ['name' => $record['name']];
            $values = [
                'description' => $record['description'],
                'num_reviews' => $record['reviews'],
                'rating' => $record['rating'],
                'phone' => $record['phone'],
                'image' => $record['featured_image'],
                'categories' => $record['categories'],
                'hours' => $record['workday_timing'],
                'address' => $record['address'],
                'tags' => $record['review_keywords'],
                'source' => 'google_maps_scraper'
            ];
            Place::updateOrCreate($attributes, $values);
        }
    }

     /**
     * import the reviews csv data from a list into the reviews table.
     */
    private function reviewsDataToTable($records) {
        foreach ($records as $record) {
            $placeName = $record['place_name'];
            $placeID = Place::where('name', $placeName)->value('id');
            $reviewerName = $record['name'];
            $rating = $record['rating'];
            if($rating == '') {
                $rating = null;
            }
            $text = $record['review_text'];
            $publishDate = $this->convertToDateTime($record['published_at_date']);
            $responseFromOwner = $record['response_from_owner_text'];
            $responseFromOwnerDate = $this->convertToDateTime($record['response_from_owner_date']);
            $reviewPhotos = $record['review_photos'];

            //Check if table entry already exists by searching the place_id, author name, and the publish date
            $attributes = [
                'place_id' => $placeID,
                'author' => $reviewerName,
                'published_at_date' => $publishDate,
            ];
            $values = [
                'place_name' => $placeName,
                'rating' => $rating,
                'text' => $text,
                'owner_response' => $responseFromOwner,
                'owner_response_published_date' => $responseFromOwnerDate,
                'review_photos' => $reviewPhotos,
                'source' => 'google_maps_scraper'
            ];
            Review::updateOrCreate($attributes, $values);
        }
    }

     /**
     * convert a raw DateTime String into a form that works for MySQL DateTime format
     */
    private function convertToDateTime($rawDateString) {
        $carbonDate = Carbon::parse($rawDateString);
        $mySqlDate = $carbonDate->format('Y-m-d H:i:s');
        return $mySqlDate;
    }

     /**
     * Check if the place file matches the review file
     */
    private function compareFileNames($placeFile, $reviewFile) {
        $place = array_slice(explode('_', $placeFile), 0, 2);
        $reviewPlace = array_slice(explode('_', $reviewFile), 0, 2);
        return $place === $reviewPlace;
    }

    /**
     * Remove places without reviews or with too few reviews
     */
    private function trimPlaces($placeRecords, $reviewRecords) {
        $reviewPlaceIds = [];
        foreach ($reviewRecords as $review) {

            $reviewPlaceIds[] = $review['place_id'];
        }
        // Filter places with less than 25 reviews using CallbackFilterIterator
        $trimByCount = new CallbackFilterIterator(
          $placeRecords, function($element) {
             return $element['reviews'] > 24;
          }
        );
        // Check if there are any reviews for the placeID
        $trimByReviews = new CallbackFilterIterator(
          $trimByCount, function($element) use ($reviewPlaceIds) {
            foreach($reviewPlaceIds as $id) {
                if($element['place_id'] == $id) {
                    return true;
                }
            }
            return false;
        });
        return $trimByReviews;
    }

    /**
     * Remove the reviews of places we removed in the trimPlaces() function
     */
    private function trimReviews($placeRecords, $newPlaceRecords, $reviewRecords) {
        $removedPlaceIds = [];
        $newReviewRecords = $reviewRecords;
        //Find which places were removed
        $newPlaceIds = [];
            foreach ($newPlaceRecords as $newRecord) {
            $newPlaceIds[] = $newRecord['place_id'];
        }
        $removedPlaceIds = [];
        foreach ($placeRecords as $oldRecord) {
             $placeID = $oldRecord['place_id'];
            if (!in_array($placeID, $newPlaceIds)) {
                 $removedPlaceIds[] = $placeID;
            }
        }
        //Remove reviews if a matching placeID was also removed
        $newReviewRecords = new CallbackFilterIterator(
            $reviewRecords, function ($element) use ($removedPlaceIds) {
            foreach ($removedPlaceIds as $id) {
                if ($element['place_id'] === $id) {
                    return false;
                }
            }
            return true;
            });
         return $newReviewRecords;
    }
}