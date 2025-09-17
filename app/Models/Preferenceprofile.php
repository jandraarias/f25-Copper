<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'interests' => 'array',
    ];

    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }
}
