<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'title',
        'description',
        'date',
        'time',
        'location',
    ];

    protected $casts = [
        'id' => 'integer',
        'itinerary_id' => 'integer',
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
}
