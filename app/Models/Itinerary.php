<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'country',      // required column
        'destination',  // optional
        'start_date',
        'end_date',
        'traveler_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItineraryItem::class);
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $user = Auth::user();
            if (! $model->traveler_id && $user && $user->traveler) {
                $model->traveler_id = $user->traveler->id;
            }
        });
    }
}
