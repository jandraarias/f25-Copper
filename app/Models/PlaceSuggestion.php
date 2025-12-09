<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'expert_suggestion_id',
        'expert_id',
        'name',
        'description',
        'location',
        'type',
        'lat',
        'lon',
        'rating',
        'num_reviews',
        'phone',
        'website',
        'google_maps_url',
        'status',
        'place_id',
    ];

    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
        'rating' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     |------------------------------------------------------------------*/

    /**
     * The expert who submitted this suggestion.
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(Expert::class);
    }

    /**
     * The expert suggestion this place belongs to.
     */
    public function expertSuggestion(): BelongsTo
    {
        return $this->belongsTo(ExpertSuggestion::class);
    }

    /**
     * If converted to a Place, the link to it.
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
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

    public function scopeConvertedToPlace($query)
    {
        return $query->where('status', 'converted_to_place');
    }

    /* -----------------------------------------------------------------
     |  Domain Logic
     |------------------------------------------------------------------*/

    /**
     * Convert this suggestion to an actual Place in the database.
     */
    public function convertToPlace(): Place
    {
        $place = Place::create([
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->location,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'rating' => $this->rating,
            'num_reviews' => $this->num_reviews ?? 0,
            'phone' => $this->phone,
            'categories' => $this->type,
            'source' => 'expert_suggestion',
        ]);

        $this->update([
            'place_id' => $place->id,
            'status' => 'converted_to_place',
        ]);

        return $place;
    }
}
