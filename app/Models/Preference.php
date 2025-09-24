<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function profile(): BelongsTo
    {
        return $this->belongsTo(PreferenceProfile::class, 'preference_profile_id');
    }
}
