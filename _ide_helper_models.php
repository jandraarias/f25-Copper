<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $country
 * @property string $location
 * @property int $traveler_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $public_uuid
 * @property string|null $destination
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItineraryItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Traveler|null $traveler
 * @method static \Database\Factories\ItineraryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary wherePublicUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereTravelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereUpdatedAt($value)
 */
	class Itinerary extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperItineraryItem
 * @property int $id
 * @property int $itinerary_id
 * @property string $type
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property string|null $location
 * @property string|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Itinerary $itinerary
 * @method static \Database\Factories\ItineraryItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereItineraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereUpdatedAt($value)
 */
	class ItineraryItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $preference_profile_id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PreferenceProfile $profile
 * @method static \Database\Factories\PreferenceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference wherePreferenceProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereValue($value)
 */
	class Preference extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $traveler_id
 * @property string $name
 * @property string|null $budget
 * @property string|null $interests
 * @property string|null $preferred_climate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Preference> $preferences
 * @property-read int|null $preferences_count
 * @property-read \App\Models\Traveler $traveler
 * @method static \Database\Factories\PreferenceProfileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereInterests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile wherePreferredClimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereTravelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceProfile whereUpdatedAt($value)
 */
	class PreferenceProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperTraveler
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $phone_number
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $bio
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $itineraries
 * @property-read int|null $itineraries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PreferenceProfile> $preferenceProfiles
 * @property-read int|null $preference_profiles_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereUserId($value)
 */
	class Traveler extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperUser
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property string|null $phone_number
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Traveler|null $traveler
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

