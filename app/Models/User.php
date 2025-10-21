<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // === Role constants ===
    public const ROLE_TRAVELER = 'traveler';
    public const ROLE_EXPERT   = 'expert';
    public const ROLE_BUSINESS = 'business';
    public const ROLE_ADMIN    = 'admin';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'date_of_birth'     => 'date', // ensures Y-m-d format on save/read
    ];

    // === Role helpers ===
    public function isTraveler(): bool
    {
        return $this->role === self::ROLE_TRAVELER;
    }

    public function isExpert(): bool
    {
        return $this->role === self::ROLE_EXPERT;
    }

    public function isBusiness(): bool
    {
        return $this->role === self::ROLE_BUSINESS;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function createdItineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function collaborativeItineraries()
    {
        return $this->belongsToMany(Itinerary::class, 'itinerary_user')->withTimestamps();
    }

    // === Relationships ===
    public function traveler()
    {
        return $this->hasOne(Traveler::class);
    }
}
