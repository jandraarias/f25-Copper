<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'dob',
        'country',
        'bio',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function preferenceProfiles()
    {
        return $this->hasMany(PreferenceProfile::class);
    }
}
