<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class PreferenceProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'traveler_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'traveler_id' => 'integer',
    ];

    /**
     * The traveler who owns this preference profile.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }

    /**
     * The preferences stored under this profile.
     */
    public function preferences(): HasMany
    {
        return $this->hasMany(Preference::class);
    }

    /**
     * Itineraries that were generated using this profile.
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'preference_profile_id');
    }

    /**
     * Automatically attach to the logged-in traveler's account.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $user = Auth::user();
            if (! $model->traveler_id && $user && $user->traveler) {
                $model->traveler_id = $user->traveler->id;
            }
        });
    }

    /**
     * Helper: convert preferences into associative array.
     */
    public function toPreferenceArray(): array
    {
        return $this->preferences
            ->mapWithKeys(fn ($pref) => [$pref->key => $pref->value])
            ->toArray();
    }
}
