<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',  // ISO alpha-2 (e.g., "US", "FR")
        'name',  // Full name (e.g., "United States", "France")
    ];

    public $timestamps = false; // countries don’t usually need timestamps

    /**
     * Itineraries that include this country.
     */
    public function itineraries(): BelongsToMany
    {
        return $this->belongsToMany(Itinerary::class, 'country_itinerary')
                    ->withTimestamps();
    }
}
