<?php

namespace App\Services;

use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Place;
use Carbon\Carbon;
use OpenAI;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;


class ItineraryGenerationService
{
    /**
     * Generate itinerary items using DB-backed Places.
     *
     * @param  Itinerary  $itinerary
     * @param  array{force?:bool}  $options
     * @return array{ok:bool, error?:string, created_count?:int, skipped?:string}
     */
    public function generateForItinerary(Itinerary $itinerary, array $options = []): array
    {
        // ------------------------------------------------------------------
        // 0) Guard clauses
        // ------------------------------------------------------------------
        if (!$itinerary->start_date || !$itinerary->end_date) {
            return ['ok' => false, 'error' => 'Itinerary dates are required'];
        }

        if (!$itinerary->preference_profile_id) {
            return ['ok' => false, 'error' => 'A preference profile must be associated'];
        }

        // City: we’ll use itinerary->location first, fall back to ->destination
        $city = trim((string)($itinerary->location ?? $itinerary->destination ?? ''));
        if ($city === '') {
            return ['ok' => false, 'error' => 'Itinerary location (city) is required'];
        }

        // Prevent duplicate generations unless forced
        $force = (bool)($options['force'] ?? false);
        if (!$force && $itinerary->items()->exists()) {
            return [
                'ok' => true,
                'created_count' => 0,
                'skipped' => 'Items already exist. Pass ["force" => true] to regenerate.'
            ];
        }

        // ------------------------------------------------------------------
        // 1) Pull preferences
        // ------------------------------------------------------------------
        $profile = $itinerary->preferenceProfile()->with('preferences')->first();
        $prefs = $profile->toPreferenceArray();

        // ------------------------------------------------------------------
        // 2) Query candidate places for city
        //     We filter by address containing the city string.
        // ------------------------------------------------------------------
        $candidates = Place::query()
            ->where('address', 'like', '%' . $city . '%')
            ->get();

        if ($candidates->isEmpty()) {
            return ['ok' => false, 'error' => "No places found for {$city}"];
        }

        // Partition into activities and food based on Place::type accessor
        [$activities, $foods] = $this->partitionByType($candidates);

        if ($activities->isEmpty() && $foods->isEmpty()) {
            return ['ok' => false, 'error' => "No usable places (food/activities) found for {$city}"];
        }

        // ------------------------------------------------------------------
        // 4) Build days and create 2 activities + 2 food per day
        // ------------------------------------------------------------------
        $days = $this->eachDate(Carbon::parse($itinerary->start_date), Carbon::parse($itinerary->end_date));

        //OpenAI Sorting
        $activities = $this->AiSelectandSort($activities, $prefs, $days);
        $foods = $this->AiSelectandSort($foods, $prefs, $days);

        //$activities = Place::orderBy('id', 'asc')->take(10)->get();
        //$foods = Place::orderBy('id', 'desc')->take(10)->get();

        $activitiesIterator = $activities->getIterator();
        $foodsIterator = $foods->getIterator();
        
        // If forcing, clear existing items first
        if ($force) {
            $itinerary->items()->delete();
        }

        $created = 0;

        foreach ($days as $day) {
           

            // Time slots: simple, tweak as needed
            $slots = [
                ['type' => 'activity', 'time' => '10:00:00', 'dur_min' => 120],
                ['type' => 'food',     'time' => '12:30:00', 'dur_min' => 60],
                ['type' => 'activity', 'time' => '15:00:00', 'dur_min' => 120],
                ['type' => 'food',     'time' => '18:30:00', 'dur_min' => 90],
            ];

           // Interleave activities and food for the day.
            foreach ($slots as $slot) {
                 $place = null;
                if ($slot['type'] === 'activity' && $activitiesIterator->valid()) {
                    $place = $activitiesIterator->current();
                    $activitiesIterator->next();
                } elseif ($slot['type'] === 'food' && $foodsIterator->valid()) {
                    $place = $foodsIterator->current();
                    $foodsIterator->next();
            }
                if($place) {
                    echo $place->name;
                    $start = Carbon::parse($day->toDateString() . ' ' . $slot['time']);
                    $end   = (clone $start)->addMinutes($slot['dur_min']);

                    ItineraryItem::create([
                        'itinerary_id' => $itinerary->id,
                        'place_id'     => $place->id,
                        'type'         => $slot['type'],
                        'title'        => $place->name,
                        'location'      => $place->address,
                        'rating'       => $place->rating,             
                        'google_maps_url' => $place->meta['maps_url'] 
                                            ?? $place->meta['google_maps'] 
                                            ?? null,                 
                        'start_time'   => $start,
                        'end_time'     => $end,
                        'details'      => $place->description 
                    ]);
                    $created++;
                }
            }
        }
        return ['ok' => true, 'created_count' => $created];
    }

    // ----------------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------------
    /**
     * Inclusive date range.
     *
     * @return array<int,Carbon>
     */
    private function eachDate(Carbon $start, Carbon $end): array
    {
        $days = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $days[] = $d->copy();
        }
        return $days;
    }

    /**
     * Partition places into [activities, foods] using Place::type accessor.
     *
     * @return array{0:Collection,1:Collection}
     */
    private function partitionByType(Collection $places): array
    {
        $foods = $places->filter(fn (Place $p) => $p->type === 'food')->values();
        $activities = $places->filter(fn (Place $p) => $p->type === 'activity')->values();
        return [$activities, $foods];
    }

    /**
     * 
     */
    private function AiSelectandSort(Collection $places, array $prefs, array $days): Collection {
        //If App is running locally we disable SSL verification
        $client = $this->getOpenAIClient();
        $numDays = count($days) + 1;
        $prefArray = [];
        foreach($prefs as $value) {
            $prefArray[] = $value;
        }
        $placeArray = [];

        $prefString = implode(', ', $prefArray);

        foreach($places as $place) {
            $placeArray[] = $place->name . "; Tags: " . $place->tags . "; " .$place->rating;
        }

        $placeString = implode(", \n" , $placeArray);

        //Prompt placed to OpenAI
        $prompt = "Given this selection of user preferences " . $prefString . "\n Select two places per day for this amount of days " . $numDays . "\n Here is the list of places
        alongside tags that match user preferences and the average rating from reviews between 1-5. Prefer places that have higher ratings and that match user preferences" . "
        Here is the list of places \n" . $placeString . "\n Give me just the names of the places as a comma seperated list";

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
                print "Retrying API call in 5 seconds. Attempts: " . $attempt;
                sleep(5);
                }
                //OpenAI Chat Completion
                $result = $client->chat()->create([
                'messages' => $messages,
                'model' => 'gpt-5-mini',
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

        //Turn the String reply into an array of place name substrings
        $reply = explode(',', $reply);
        $chosenPlaces = new Collection([]);

        foreach($reply as $AiPlace) {
            $entry = Place::where('name', trim($AiPlace))->first();
            $chosenPlaces->push($entry);
        }
        return $chosenPlaces;
    }

    /**
     * 
     */
    private function getOpenAIClient() {
        $apikey = getenv('OPENAI_API_KEY');
        if (App::isLocal()) {
            $httpClient = new Client([
                'verify' => false, // This is the option to disable SSL verification
            ]);
            $clientLocal = OpenAI::factory()
                ->withAPIKey($apikey)
                ->withHttpClient($httpClient)
                ->make();
            return $clientLocal;
        }
        else {
            $clientLive = OpenAI::client($apikey);
            return $clientLive;
        }
        
    }

}
