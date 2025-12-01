<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Place extends Model
{
    use HasFactory;

    /**
     * Columns are simple (see schema). 'meta' contains extra fields
     * from the import process (address, keywords, hours, etc.).
     */
    protected $fillable = [
        'name',
        'description',
        'num_reviews',
        'phone',
        'address',
        'lat',
        'lon',
        'rating',
        'categories',
        'tags',
        'image',
        'source',
        'meta',
        'photo_url',
    ];

    protected $casts = [
        'meta' => 'array',
        'lat' => 'float',
        'lon' => 'float',
        'rating' => 'float',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     |------------------------------------------------------------------*/

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function itineraryItems()
    {
        return $this->hasMany(ItineraryItem::class);
    }

    public function rewards(){
        return $this->hasMany(Reward::class);
    }

    public function preferenceOptions()
{
    //Need to explicitly specific columns so that it doesn't get place.id and preference_options.id confused
    return $this->belongsToMany(PreferenceOption::class, 'place_preference_option')->select('preference_options.id', 'name', 'type', 'parent_id');
}

    /* -----------------------------------------------------------------
     |  Accessors – expose normalized fields from meta
     |------------------------------------------------------------------*/
    /**
     * Price level extracted from meta (may be numeric or "$$").
     */

    protected function mainCategory(): Attribute
    {
        return Attribute::get(fn () => $this->meta['main_category'] ?? $this->category);
    }

    protected function priceLevel(): Attribute
    {
        $value = $this->meta['price_level'] ?? null;

        if (is_numeric($value)) {
            return Attribute::get(fn () => (int) $value);
        }

        if (is_string($value)) {
            return Attribute::get(fn () => strlen(Str::of($value)->replaceMatches('/[^$]/', '')));
        }

        return Attribute::get(fn () => null);
    }
    /**
     * Distinguish between "food" and "activity" based on prference_options parent_ids text.
     */
    protected function type(): Attribute
    {
        return Attribute::get(function () {
            //These ids correspond to "food" categories in the preference_option table
            $foodParentIds = [12, 17, 30];
            
            $options = $this->relationLoaded('preferenceOptions')
            ? $this->preferenceOptions
            : $this->preferenceOptions()->get(['id', 'parent_id']);

            // Check if any related preference option has a food parent
            $isFood = $options->contains(fn($opt) =>
            in_array($opt->parent_id, $foodParentIds)
            );
            return $isFood ? 'food' : 'activity';
        });
    }

    /**
     * Returns a valid photo URL OR a clean fallback Unsplash image.
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(function () {

            // Use explicit photo_url if provided
            if (!empty($this->attributes['photo_url'])) {
                return $this->attributes['photo_url'];
            }

            // Fallback to "image" column if photo_url not provided
            if (!empty($this->attributes['image'])) {
                return $this->attributes['image'];
            }

            // Elegant unsplash fallback based on type
            $keyword = $this->type === 'food' ? 'restaurant' : 'travel';

            return "https://source.unsplash.com/800x600/?{$keyword}";
        });
    }

    protected $appends = [
        'price_level',
        'type',

        // Append resolved photo URL to API & Blade output
        'photo_url',
    ];

    /* -----------------------------------------------------------------
     |  Scopes
     |------------------------------------------------------------------*/

    public function scopeNearby($query, float $lat, float $lon, float $radiusKm = 10)
    {
        $latRange = $radiusKm / 111;
        $lonRange = $radiusKm / (111 * cos(deg2rad($lat)));

        return $query
            ->whereBetween('lat', [$lat - $latRange, $lat + $latRange])
            ->whereBetween('lon', [$lon - $lonRange, $lon + $lonRange]);
    }

    public function scopeHighlyRated($query, float $min = 4.0)
    {
        return $query->where('rating', '>=', $min);
    }

    public function scopeOfType($query, string $type)
    {
        $type = strtolower($type);

        if ($type === 'food') {
            return $query->where(function ($q) {
                $q->where('category', 'like', '%restaurant%')
                  ->orWhere('category', 'like', '%food%')
                  ->orWhere('category', 'like', '%cafe%')
                  ->orWhere('category', 'like', '%bar%');
            });
        }

        return $query->where(function ($q) {
            $q->whereNull('category')
              ->orWhere(function ($q2) {
                  $q2->where('category', 'not like', '%restaurant%')
                      ->where('category', 'not like', '%food%')
                      ->where('category', 'not like', '%cafe%')
                      ->where('category', 'not like', '%bar%');
              });
        });
    }

    public function scopeHasAnyTags($query, array $tags)
    {
        return $query->whereHas('preferenceOptions', function ($q) use ($tags) {
        $q->whereIn('name', $tags);
        });
    }

    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', 'like', "%{$category}%");
    }
}
