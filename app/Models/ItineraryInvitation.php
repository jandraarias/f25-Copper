<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class ItineraryInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'email',
        'token',
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
     * The itinerary this invitation belongs to.
     */
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Boot (Auto-generate token)
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::uuid()->toString();
            }
            if (empty($invitation->status)) {
                $invitation->status = 'pending';
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Determine if the invitation has expired.
     * You could later add expiration logic (e.g., expires after 14 days).
     */
    public function isExpired(): bool
    {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
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
     * Accept the invitation and attach the user as a collaborator.
     */
    public function accept(User $user): void
    {
        if ($this->status !== 'pending') {
            return; // prevent double acceptance
        }

        $this->update(['status' => 'accepted']);

        // Attach user to itinerary if not already attached
        if (!$this->itinerary->collaborators()->where('user_id', $user->id)->exists()) {
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
