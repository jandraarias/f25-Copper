<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'place_name',
        'author',
        'source',
        'rating',
        'text',
        'published_at_date',
        'owner_response',
        'owner_response_publish_date',
        'fetched_at',
        'review_photos',
        'meta'
    ];

    protected $casts = [
        'published_at_date' => 'datetime',
        'owner_response_publish_date' => 'datetime',
        'fetched_at' => 'datetime',
        'meta' => 'array',
    ];

    public function place() {
        return $this->belongsTo(Place::class);
    }
}