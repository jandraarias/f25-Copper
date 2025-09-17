<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function preferenceProfiles(): BelongsToMany
    {
        return $this->belongsToMany(
            PreferenceProfile::class,
            'preference_preference_profile',
            'preference_id',
            'preference_profile_id'
        );
    }
}
