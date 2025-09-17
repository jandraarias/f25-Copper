<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'traveler_id',
        'title',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }

    public function items()
    {
        return $this->hasMany(ItineraryItem::class);
    }
}
