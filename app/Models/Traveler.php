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
        if (!empty($this->profile_photo_path)) {
            return asset('storage/' . ltrim($this->profile_photo_path, '/'));
        }

        return asset('data/images/defaults/traveler.png');
    }
}
