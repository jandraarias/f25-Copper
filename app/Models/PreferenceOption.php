<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferenceOption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'parent_id', 'category'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PreferenceOption::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(PreferenceOption::class, 'parent_id');
    }

    public function places()
    {
        return $this->belongsToMany(Place::class, 'place_preference_option');
    }
}

