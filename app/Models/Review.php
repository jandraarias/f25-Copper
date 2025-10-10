<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'source',
        'rating',
        'text',
        'published_at_date',
        'fetched_at', 
        'meta'
    ];

    protected $casts = [
        'published_at_date' => 'datetime',
        'fetched_at' => 'datetime',
        'meta' => 'array',
    ];

    public function place() {
        return $this->belongsTo(Place::class);
    }
}