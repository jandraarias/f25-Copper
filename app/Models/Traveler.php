<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTraveler
 */
class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio', // traveler-specific only
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function preferenceProfiles(): HasMany
    {
        return $this->hasMany(PreferenceProfile::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\TravelerFactory::new();
    }
}
