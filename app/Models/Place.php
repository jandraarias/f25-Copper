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

    /* -----------------------------------------------------------------
     |  Accessors – expose normalized fields from meta
     |------------------------------------------------------------------*/
    /**
     * Price level extracted from meta (may be numeric or "$$").
     */
    protected function priceLevel(): Attribute
    {
        $value = $this->meta['price_level'] ?? null;
        if (is_numeric($value)) {
            return Attribute::get(fn () => (int) $value);
        }
        if (is_string($value)) {
            // Map "$" → 1, "$$" → 2, "$$$" → 3, "$$$$" → 4
            return Attribute::get(fn () => strlen(Str::of($value)->replaceMatches('/[^$]/', '')));
        }
        return Attribute::get(fn () => null);
    }
    /**
     * Distinguish between "food" and "activity" based on category text.
     */
    protected function type(): Attribute
    {
        return Attribute::get(function () {
            $isFood = str_contains($this->tags, 'Cuisine');
            return $isFood ? 'food' : 'activity';
});
    }

    protected $appends = [
        'price_level',
        'type',
    ];

    /* -----------------------------------------------------------------
     |  Scopes – helpers for querying and filtering
     |------------------------------------------------------------------*/

    /**
     * Filter by approximate location coordinates.
     */
    public function scopeNearby($query, float $lat, float $lon, float $radiusKm = 10)
    {
        $latRange = $radiusKm / 111;
        $lonRange = $radiusKm / (111 * cos(deg2rad($lat)));

        return $query
            ->whereBetween('lat', [$lat - $latRange, $lat + $latRange])
            ->whereBetween('lon', [$lon - $lonRange, $lon + $lonRange]);
    }

    /**
     * Get only places rated >= min.
     */
    public function scopeHighlyRated($query, float $min = 4.0)
    {
        return $query->where('rating', '>=', $min);
    }


    /**
     * Filter by main category string.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', 'like', "%{$category}%");
    }
}
