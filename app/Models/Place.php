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
    public function rewards(){
        return $this->hasMany(Reward::class);
    }

    /* -----------------------------------------------------------------
     |  Accessors – expose normalized fields from meta
     |------------------------------------------------------------
     * Returns address if present in meta.
     */
    protected function address(): Attribute
    {
        return Attribute::get(fn () => $this->meta['address'] ?? null);
    }

    /**
     * Returns the main category (either column or meta override).
     */
    protected function mainCategory(): Attribute
    {
        return Attribute::get(fn () => $this->meta['main_category'] ?? $this->category);
    }

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
     * Keywords/tags parsed from meta.
     */
    protected function tags(): Attribute
    {
        return Attribute::get(function () {
            $raw = $this->meta['review_keywords']
                ?? $this->meta['tags']
                ?? null;

            if (is_string($raw)) {
                $tags = preg_split('/\s*,\s*/', $raw);
            } elseif (is_array($raw)) {
                $tags = $raw;
            } else {
                $tags = [];
            }

            return collect($tags)
                ->map(fn ($t) => strtolower(trim($t)))
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        });
    }

    /**
     * Distinguish between "food" and "activity" based on category text.
     */
    protected function type(): Attribute
    {
        return Attribute::get(function () {
            $main = strtolower((string) ($this->meta['main_category'] ?? $this->category ?? ''));
            $hints = ['restaurant', 'food', 'bar', 'cafe', 'brunch', 'bakery', 'pub'];
            $isFood = collect($hints)->contains(fn ($h) => str_contains($main, $h));
            return $isFood ? 'food' : 'activity';
        });
    }

    protected $appends = [
        'address',
        'main_category',
        'price_level',
        'tags',
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
     * Filter by type (food or activity) using category hints.
     */
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

        // Default: non-food activities
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

    /**
     * Filter by tag keywords (checks inside meta JSON).
     */
    public function scopeHasAnyTags($query, array $tags)
    {
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhere('meta->review_keywords', 'like', "%{$tag}%");
            }
        });
    }

    /**
     * Filter by main category string.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', 'like', "%{$category}%");
    }
}
