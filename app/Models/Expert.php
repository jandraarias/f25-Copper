<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'photo_url',
        'bio',
    ];

    // Relationship: Expert has many reviews
    public function reviews()
    {
        return $this->hasMany(ExpertReview::class);
    }
}
