<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferenceProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'traveler_id',
        'name',
        'budget',
        'interests',
        'preferred_climate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'interests' => 'array',
        'budget' => 'decimal:2',
    ];

    /**
     * Get the traveler that owns this preference profile.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }
}
