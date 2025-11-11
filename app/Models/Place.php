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

    /* -----------------------------------------------------------------
     |  Accessors – expose normalized fields from meta
     |------------------------------------------------------------------*/

    protected function address(): Attribute
    {
        return Attribute::get(fn () => $this->meta['address'] ?? null);
    }

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

    protected function type(): Attribute
    {
        return Attribute::get(function () {
            $main = strtolower((string) ($this->meta['main_category'] ?? $this->category ?? ''));
            $hints = ['restaurant', 'food', 'bar', 'cafe', 'brunch', 'bakery', 'pub'];
            $isFood = collect($hints)->contains(fn ($h) => str_contains($main, $h));
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
        'address',
        'main_category',
        'price_level',
        'tags',
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
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhere('meta->review_keywords', 'like', "%{$tag}%");
            }
        });
    }

    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', 'like', "%{$category}%");
    }
}
