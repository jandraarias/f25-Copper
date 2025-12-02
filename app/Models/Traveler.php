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
        'profile_photo_path',
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

    // === Accessor ===
    public function getProfilePhotoUrlAttribute(): string
    {
        // If custom uploaded profile photo exists
        if ($this->profile_photo_path && file_exists(public_path('storage/' . $this->profile_photo_path))) {
            return asset('storage/' . $this->profile_photo_path);
        }

        // Correct default path
        $defaultPath = 'storage/images/defaults/traveler.png';

        // Check the file really exists in public/
        if (file_exists(public_path($defaultPath))) {
            return asset($defaultPath);
        }

        // Ultimate fallback (prevents broken images)
        return 'https://via.placeholder.com/150?text=Traveler';
    }
}
