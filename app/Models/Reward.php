<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'title',
        'description',
        'discount_code',
        'expires_at',
    ];

    /**
     * A reward belongs to a specific place.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
