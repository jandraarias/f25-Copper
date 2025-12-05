<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Fillable (MATCHES MIGRATION)
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'place_id',
        'place_name',

        // Reviewer info
        'reviewer_name',
        'reviewer_id',
        'reviewer_profile',

        // Core review text
        'review_text',
        'text_translated',

        // Dates
        'reviewed_at',
        'published_at_date',

        // Owner response
        'owner_response',
        'owner_response_translated',
        'owner_response_publish_date',

        // Structured data
        'experience_details',    // JSON array
        'review_photos',         // JSON array
        'meta',                  // JSON blob

        // Source + metadata
        'source',
        'fetched_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'rating'                        => 'float',

        'reviewed_at'                   => 'date',
        'published_at_date'             => 'datetime',
        'owner_response_publish_date'   => 'datetime',

        'experience_details'            => 'array',
        'review_photos'                 => 'array',
        'meta'                           => 'array',

        'fetched_at'                    => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Backwards-Compatible Shims
    | Allows legacy code using ->author and ->text to keep working.
    |--------------------------------------------------------------------------
    */

    // old ->author
    public function getAuthorAttribute()
    {
        return $this->reviewer_name;
    }
    public function setAuthorAttribute($value)
    {
        $this->reviewer_name = $value;
    }

    // old ->text
    public function getTextAttribute()
    {
        return $this->review_text;
    }
    public function setTextAttribute($value)
    {
        $this->review_text = $value;
    }

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
    | Derived / Virtual Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Primary avatar or profile photo URL, falling back to meta.
     */
    public function getAvatarAttribute()
    {
        return $this->reviewer_profile
            ?? ($this->meta['reviewer_profile'] ?? null);
    }

    /**
     * Preferred display name.
     */
    public function getReviewerNameAttribute()
    {
        return $this->reviewer_name
            ?? ($this->meta['name'] ?? 'Anonymous');
    }

    /**
     * Short excerpt version of the review for UI cards.
     */
    public function getExcerptAttribute()
    {
        if (!$this->review_text) {
            return null;
        }

        return Str::limit(strip_tags($this->review_text), 180);
    }

    /**
     * Cleanly returns structured experience detail pairs.
     */
    public function getExperienceListAttribute()
    {
        $list = $this->experience_details ?? [];

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
     * Returns array of photo URLs extracted from review_photos objects.
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
