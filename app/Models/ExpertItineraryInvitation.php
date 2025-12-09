<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertItineraryInvitation extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
    public const STATUS_PENDING  = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'itinerary_id',
        'expert_id',
        'traveler_id',
        'status',
        'token',       // NEW — used for invitation link routing
        'message',     // NEW — traveler → expert message
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** The itinerary this invitation applies to. */
    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    /** The invited expert. */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(Expert::class);
    }

    /** The traveler who owns the itinerary / issued the invitation. */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', self::STATUS_DECLINED);
    }

    /*
    |--------------------------------------------------------------------------
    | Domain Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Accept the invitation.
     *
     * Also attaches the expert's User to the itinerary collaborators,
     * mirroring traveler-based invitation behavior.
     */
    public function accept(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            return;
        }

        $this->update(['status' => self::STATUS_ACCEPTED]);

        // Attach expert's user to collaborators table
        $user = $this->expert->user;

        if ($user && !$this->itinerary->collaborators()->where('user_id', $user->id)->exists()) {
            $this->itinerary->collaborators()->attach($user->id);
        }
    }

    /**
     * Decline the invitation.
     */
    public function decline(): void
    {
        if ($this->status === self::STATUS_PENDING) {
            $this->update(['status' => self::STATUS_DECLINED]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isDeclined(): bool
    {
        return $this->status === self::STATUS_DECLINED;
    }
}
