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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * The traveler who owns the itinerary.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
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
        return $this->belongsToMany(
            Country::class,          // The model representing countries
            'country_itinerary',     // Pivot table
            'itinerary_id',          // Foreign key on pivot for itinerary
            'country_code'           // Foreign key on pivot for country
        );
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $user = Auth::user();
            if (! $model->traveler_id && $user && $user->traveler) {
                $model->traveler_id = $user->traveler->id;
            }
        });
    }
}
