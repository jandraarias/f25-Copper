<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPreference
 */
class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'preference_profile_id',
        'key',
        'value',
    ];

    protected $casts = [
        'id' => 'integer',
        'preference_profile_id' => 'integer',
    ];

    public function preferenceProfile(): BelongsTo
    {
        return $this->belongsTo(PreferenceProfile::class);
    }
}
