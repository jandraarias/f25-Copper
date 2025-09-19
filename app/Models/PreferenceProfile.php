<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPreferenceProfile
 */
class PreferenceProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'traveler_id',
        'name',
        'budget',
        'interests',
        'preferred_climate',
    ];

    protected $casts = [
        'id' => 'integer',
        'traveler_id' => 'integer',
        'interests' => 'array',
    ];

    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }

    public function preferences(): HasMany
    {
        return $this->hasMany(Preference::class);
    }
}
