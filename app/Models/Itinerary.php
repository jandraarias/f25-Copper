<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'traveler_id',
        'destination',
        'location',               // ✅ city/location support
        'preference_profile_id',  // ✅ foreign key to PreferenceProfile
        'is_collaborative',       // ✅ for easier mass assignment
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_collaborative' => 'boolean', // ✅ ensures consistent type
    ];

    /**
     * The traveler who owns the itinerary.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }

    /**
     * The preference profile this itinerary is based on.
     */
    public function preferenceProfile(): BelongsTo
    {
        return $this->belongsTo(PreferenceProfile::class, 'preference_profile_id');
    }

    /**
     * Items belonging to the itinerary.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ItineraryItem::class);
    }

    /**
     * Countries associated with this itinerary.
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_itinerary')
            ->withTimestamps();
    }

    /**
     * Auto-assign the traveler's ID when creating.
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
     * Invitations sent for collaboration.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(ItineraryInvitation::class);
    }

    /**
     * Collaborators who can edit this itinerary.
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'itinerary_user')->withTimestamps();
    }

    /**
     * The original creator (User model reference).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper method for readability.
     */
    public function isCollaborative(): bool
    {
        return (bool) $this->is_collaborative;
    }
}
