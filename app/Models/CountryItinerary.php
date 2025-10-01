<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryItinerary extends Model
{
    use HasFactory;

    protected $table = 'country_itinerary';

    protected $fillable = [
        'itinerary_id',
        'country',
    ];

    /**
     * Parent itinerary this country belongs to.
     */
    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
}
