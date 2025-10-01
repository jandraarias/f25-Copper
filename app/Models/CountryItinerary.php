<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CountryItinerary extends Model
{
    protected $table = 'country_itinerary';

    protected $fillable = [
        'itinerary_id',
        'country_id',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
