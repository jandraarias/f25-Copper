<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'dob',
        'country',
        'bio',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'dob' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function preferenceProfiles(): HasMany
    {
        return $this->hasMany(PreferenceProfile::class);
    }
}
