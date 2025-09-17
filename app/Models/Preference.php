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
        'description',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    /**
     * A preference can belong to many preference profiles.
     */
    public function preferenceProfiles(): BelongsToMany
    {
        return $this->belongsToMany(
            Preferenceprofile::class,
            'preference_preference_profile',
            'preference_id',
            'preference_profile_id'
        );
    }
}
