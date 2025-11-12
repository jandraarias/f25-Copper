<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'place_name',

        // Reviewer
        'author',
        'reviewer_id',
        'reviewer_profile',

        // Core review
        'rating',
        'text',
        'text_translated',

        // Timestamps
        'published_at',
        'published_at_date',

        // Owner response
        'owner_response',
        'owner_response_translated',
        'owner_response_publish_date',

        // Structured extra data
        'experience_details',  // JSON array
        'review_photos',       // JSON array of objects
        'meta',                // JSON catch-all

        // Source and import timestamp
        'source',
        'fetched_at',
    ];

    protected $casts = [
        'rating'                    => 'float',

        'published_at'              => 'datetime',
        'published_at_date'         => 'datetime',

        'owner_response_publish_date' => 'datetime',

        'experience_details'        => 'array',
        'review_photos'             => 'array',

        'meta'                      => 'array',
        'fetched_at'                => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function place()
    {
        return $this->belongsTo(Place::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors (Virtual Attributes for UI)
    |--------------------------------------------------------------------------
    */

    /**
     * Primary avatar or initials extracted from meta->reviewer_profile.
     */
    public function getAvatarAttribute()
    {
        $url = $this->reviewer_profile
            ?? ($this->meta['reviewer_profile'] ?? null);

        return $url ?: null;
    }

    /**
     * Preferred display name of reviewer.
     */
    public function getReviewerNameAttribute()
    {
        return $this->author
            ?? ($this->meta['name'] ?? 'Anonymous');
    }

    /**
     * Shortens long review text for excerpt displays.
     */
    public function getExcerptAttribute()
    {
        if (!$this->text) {
            return null;
        }

        return Str::limit(strip_tags($this->text), 180);
    }

    /**
     * Return decoded experience detail pairs cleanly.
     * Example: [["name" => "Food", "value" => 5], ...]
     */
    public function getExperienceListAttribute()
    {
        $list = $this->experience_details ?? [];

        // Clean numeric strings
        return collect($list)->map(function ($item) {
            if (is_array($item) && isset($item['value'])) {
                if (is_numeric($item['value'])) {
                    $item['value'] = +$item['value'];
                }
            }
            return $item;
        })->values()->toArray();
    }


    /**
     * Returns photos as a simple array of URLs.
     */
    public function getPhotoUrlsAttribute()
    {
        if (empty($this->review_photos)) {
            return [];
        }

        return collect($this->review_photos)
            ->pluck('url')
            ->filter()
            ->values()
            ->toArray();
    }
}
