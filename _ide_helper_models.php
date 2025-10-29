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
 * @property string $code
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $itineraries
 * @property-read int|null $itineraries_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $itinerary_id
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\Itinerary $itinerary
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary whereItineraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryItinerary whereUpdatedAt($value)
 */
	class CountryItinerary extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $traveler_id
 * @property string|null $public_uuid
 * @property string $name
 * @property string|null $destination
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string|null $description
 * @property bool $is_collaborative
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $preference_profile_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $collaborators
 * @property-read int|null $collaborators_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Country> $countries
 * @property-read int|null $countries_count
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItineraryInvitation> $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItineraryItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\PreferenceProfile|null $preferenceProfile
 * @property-read \App\Models\Traveler $traveler
 * @method static \Database\Factories\ItineraryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereIsCollaborative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary wherePreferenceProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary wherePublicUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereTravelerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Itinerary whereUpdatedAt($value)
 */
	class Itinerary extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $itinerary_id
 * @property string $email
 * @property string $token
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Itinerary $itinerary
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation accepted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation declined()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereItineraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryInvitation whereUpdatedAt($value)
 */
	class ItineraryInvitation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $itinerary_id
 * @property string $type
 * @property string $title
 * @property string $location
 * @property string|null $rating
 * @property \Illuminate\Support\Carbon|null $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property string|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $place_id
 * @property-read mixed $duration
 * @property-read \App\Models\Itinerary $itinerary
 * @property-read mixed $label
 * @property-read \App\Models\Place|null $place
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem between(\Illuminate\Support\Carbon|string $start, \Illuminate\Support\Carbon|string $end)
 * @method static \Database\Factories\ItineraryItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem forDay(\Illuminate\Support\Carbon|string $date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereItineraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItineraryItem whereRating($value)
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
 * @property string $name
 * @property float|null $lat
 * @property float|null $lon
 * @property float|null $rating
 * @property string|null $category
 * @property string $source
 * @property array<array-key, mixed>|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $address
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItineraryItem> $itineraryItems
 * @property-read int|null $itinerary_items_count
 * @property-read mixed $main_category
 * @property-read mixed $price_level
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read mixed $tags
 * @property-read mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place hasAnyTags(array $tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place highlyRated(float $min = 4)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place inCategory(string $category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place nearby(float $lat, float $lon, float $radiusKm = 10)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereUpdatedAt($value)
 */
	class Place extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $preference_profile_id
 * @property int|null $parent_id
 * @property string $key
 * @property string $value
 * @property string $requirement
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference wherePreferenceProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereRequirement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Preference whereValue($value)
 */
	class Preference extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $category
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PreferenceOption> $children
 * @property-read int|null $children_count
 * @property-read PreferenceOption|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreferenceOption whereUpdatedAt($value)
 */
	class PreferenceOption extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $itineraries
 * @property-read int|null $itineraries_count
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
 * @property int $id
 * @property int $place_id
 * @property string $source
 * @property string|null $author
 * @property int|null $rating
 * @property string|null $text
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $published_at_date
 * @property \Illuminate\Support\Carbon|null $fetched_at
 * @property array<array-key, mixed>|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Place $place
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereFetchedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review wherePublishedAtDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * @mixin IdeHelperTraveler
 * @property int $id
 * @property int $user_id
 * @property string|null $bio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $itineraries
 * @property-read int|null $itineraries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PreferenceProfile> $preferenceProfiles
 * @property-read int|null $preference_profiles_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TravelerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Traveler whereId($value)
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
 * @property string $role
 * @property string|null $phone_number
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $collaborativeItineraries
 * @property-read int|null $collaborative_itineraries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $createdItineraries
 * @property-read int|null $created_itineraries_count
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

