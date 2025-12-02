<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'profile_photo_path',  // uploaded local file
        'photo_url',           // optional remote URL (if you add it later)
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class, 'traveler_id');
    }

    public function preferenceProfiles()
    {
        return $this->hasMany(PreferenceProfile::class, 'traveler_id');
    }

    /**
     * Unified Traveler Profile Photo Accessor
     *
     * Priority:
     * 1. Local uploaded image
     * 2. Remote URL (photo_url)
     * 3. Default traveler image
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        $path = $this->profile_photo_path;

        // Remote image URL?
        if ($path && str_starts_with($path, 'http')) {
            return $path;
        }

        // Local file?
        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }

        // Optional seeded remote URL?
        if (!empty($this->photo_url)) {
            return $this->photo_url;
        }

        // Default traveler photo
        return asset('storage/images/defaults/traveler.png');
    }
}
