<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertItineraryInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'expert_id',
        'traveler_id',
        'status',
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

    /**
     * The itinerary this invitation is for.
     */
    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    /**
     * The expert being invited.
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(Expert::class);
    }

    /**
     * The traveler who created the itinerary.
     */
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
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    /*
    |--------------------------------------------------------------------------
    | Domain Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Accept the invitation and attach the expert's user as a collaborator.
     */
    public function accept(): void
    {
        if ($this->status !== 'pending') {
            return;
        }

        $this->update(['status' => 'accepted']);

        // Attach expert's user to itinerary as collaborator if not already attached
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
        if ($this->status === 'pending') {
            $this->update(['status' => 'declined']);
        }
    }
}
