<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'city',
        'profile_photo_path', // local file upload
        'photo_url',          // remote URL from seeder (optional)
        'bio',
        'expertise',
        'languages',
        'experience_years',
        'hourly_rate',
        'availability',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(ExpertReview::class);
    }

    public function itineraryInvitations()
    {
        return $this->hasMany(ExpertItineraryInvitation::class);
    }

    /**
     * Expert suggestions made by this expert.
     */
    public function suggestions()
    {
        return $this->hasMany(ExpertSuggestion::class);
    }

    /**
     * Place suggestions submitted by this expert.
     */
    public function placeSuggestions()
    {
        return $this->hasMany(PlaceSuggestion::class);
    }

    /**
     * Profile Photo Accessor (Unified)
     *
     * Priority:
     * 1. Uploaded image stored in storage/
     * 2. Remote photo URL from seeder
     * 3. Default expert image
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        $path = $this->profile_photo_path;

        // If stored as a full remote URL (https://...)
        if ($path && str_starts_with($path, 'http')) {
            return $path;
        }

        // If uploaded and stored locally
        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }

        // If seeded remote photo exists
        if (!empty($this->photo_url)) {
            return $this->photo_url;
        }

        // Default image
        return asset('storage/images/defaults/expert.png');
    }
}
