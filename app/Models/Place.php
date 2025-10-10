<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lat',
        'lon',
        'category',
        'source',
        'meta',
        'rating',
    ];
    
    protected $casts = [
        'meta' => 'array'
    ];

    public function reviews() {
        return $this->hasMany(Review::class);
    }
    
}
