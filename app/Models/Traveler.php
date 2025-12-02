<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\Itinerary;
use App\Models\PreferenceProfile;


class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'user_id', 'user_id');
    }

    public function preferenceProfiles(): HasMany
    {
        return $this->hasMany(PreferenceProfile::class, 'user_id', 'user_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\TravelerFactory::new();
    }
}
