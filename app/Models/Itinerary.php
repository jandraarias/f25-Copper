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
        'location',
        'preference_profile_id',
        'is_collaborative',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'is_collaborative' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot Logic
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $user = Auth::user();
            if (!$model->traveler_id && $user && $user->traveler) {
                $model->traveler_id = $user->traveler->id;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Core Relations
    |--------------------------------------------------------------------------
    */

    /** Owner: Traveler */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }

    /** Optional preference profile */
    public function preferenceProfile(): BelongsTo
    {
        return $this->belongsTo(PreferenceProfile::class, 'preference_profile_id');
    }

    /** Itinerary items (places, activities, etc.) */
    public function items(): HasMany
    {
        return $this->hasMany(ItineraryItem::class);
    }

    /** Countries associated with this itinerary */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_itinerary')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Collaboration (Travelers)
    |--------------------------------------------------------------------------
    */

    /** Pending email-based invitations (travelers) */
    public function invitations(): HasMany
    {
        return $this->hasMany(ItineraryInvitation::class);
    }

    /** Traveler collaborators */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'itinerary_user')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Collaboration (Local Experts)
    |--------------------------------------------------------------------------
    */

    /** All expert invitations (pending, accepted, declined) */
    public function expertInvitations(): HasMany
    {
        return $this->hasMany(ExpertItineraryInvitation::class);
    }

    /** Only pending expert invitations */
    public function pendingExpertInvitations(): HasMany
    {
        return $this->hasMany(ExpertItineraryInvitation::class)
            ->where('status', 'pending');
    }

    /** Experts who have accepted */
    public function experts(): BelongsToMany
    {
        return $this->belongsToMany(Expert::class, 'expert_itinerary_invitations', 'itinerary_id', 'expert_id')
            ->wherePivot('status', 'accepted');
    }

    /*
    |--------------------------------------------------------------------------
    | Creator (Optional — legacy support)
    |--------------------------------------------------------------------------
    */

    /** The original creator (backwards compatibility) */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isCollaborative(): bool
    {
        return (bool) $this->is_collaborative;
    }
}
