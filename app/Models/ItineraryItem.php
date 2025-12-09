<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ItineraryItem extends Model
{
    use HasFactory;

    /**
     * Columns available on itinerary_items table:
     * id, itinerary_id, place_id (new), type, title, location,
     * start_time, end_time, details, created_at, updated_at
     */
    protected $fillable = [
        'itinerary_id',
        'place_id',
        'type',           // 'activity' or 'food'
        'title',
        'location',    
        'rating',  
        'google_maps_url',
        'start_time',
        'end_time',
        'details',
    ];


    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     |------------------------------------------------------------------*/

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function place(): BelongsTo
    {
        // Optional: not every itinerary item must come from a Place
        return $this->belongsTo(Place::class);
    }

    /**
     * Expert suggestions for replacing this item.
     */
    public function expertSuggestions()
    {
        return $this->hasMany(ExpertSuggestion::class);
    }

    /* -----------------------------------------------------------------
     |  Accessors / Mutators
     |------------------------------------------------------------------*/

    /**
     * Accessor for duration (in minutes) if start & end times are set.
     */
    protected function duration(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->start_time || !$this->end_time) {
                return null;
            }

            return Carbon::parse($this->start_time)->diffInMinutes($this->end_time);
        });
    }

    /**
     * Accessor for a human-readable label (used in views or exports).
     */
    protected function label(): Attribute
    {
        return Attribute::get(function () {
            $type = ucfirst($this->type ?? 'Item');
            return "{$type}: {$this->title}";
        });
    }

    protected $appends = [
        'duration',
        'label',
    ];

    /* -----------------------------------------------------------------
     |  Scopes
     |------------------------------------------------------------------*/

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForDay($query, string|Carbon $date)
    {
        $date = Carbon::parse($date)->toDateString();
        return $query->whereDate('start_time', $date);
    }

    public function scopeBetween($query, string|Carbon $start, string|Carbon $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_time', [$start, $end])
              ->orWhereBetween('end_time', [$start, $end]);
        });
    }
}
