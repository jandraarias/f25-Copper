<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTraveler
 */
class Traveler extends Model
{
    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'phone_number',
        'bio',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\TravelerFactory::new();
    }

}
