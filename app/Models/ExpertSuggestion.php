<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_item_id',
        'expert_id',
        'place_id',
        'type',
        'status',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     |------------------------------------------------------------------*/

    /**
     * The itinerary item being suggested for replacement.
     */
    public function itineraryItem(): BelongsTo
    {
        return $this->belongsTo(ItineraryItem::class);
    }

    /**
     * The expert making the suggestion.
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(Expert::class);
    }

    /**
     * The suggested replacement place (if replacing from existing places).
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class)->withTrashed();
    }

    /**
     * If this suggestion is for a new place, get the place suggestion.
     */
    public function placeSuggestion(): BelongsTo
    {
        return $this->belongsTo(PlaceSuggestion::class, 'id', 'expert_suggestion_id');
    }

    /* -----------------------------------------------------------------
     |  Scopes
     |------------------------------------------------------------------*/

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeReplacement($query)
    {
        return $query->where('type', 'replacement');
    }

    public function scopeNewPlace($query)
    {
        return $query->where('type', 'new_place');
    }

    /* -----------------------------------------------------------------
     |  Domain Logic
     |------------------------------------------------------------------*/

    /**
     * Approve the suggestion and apply it to the itinerary item.
     */
    public function approve(): void
    {
        if ($this->status !== 'pending') {
            return;
        }

        if ($this->type === 'replacement' && $this->place_id) {
            // Update the itinerary item with the new place
            $this->itineraryItem->update([
                'place_id' => $this->place_id,
                'title' => $this->place->name,
                'location' => $this->place->address ?? $this->place->location,
            ]);
        }

        $this->update(['status' => 'approved']);
    }

    /**
     * Reject the suggestion.
     */
    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }
}
