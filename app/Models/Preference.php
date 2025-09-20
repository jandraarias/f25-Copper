<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Preference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'category',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    public function preferenceProfiles(): BelongsToMany
    {
        return $this->belongsToMany(
            PreferenceProfile::class,
            'preference_preference_profile',
            'preference_id',         // foreign key for this model in pivot table
            'preference_profile_id'  // foreign key for related model in pivot table
        );
    }
}
