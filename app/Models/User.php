<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // === Role constants ===
    public const ROLE_TRAVELER = 'traveler';
    public const ROLE_EXPERT   = 'expert';
    public const ROLE_BUSINESS = 'business';
    public const ROLE_ADMIN    = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'date_of_birth',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'date_of_birth'     => 'date',
    ];

    // === Role helpers ===
    public function isTraveler(): bool { return $this->role === self::ROLE_TRAVELER; }
    public function isExpert(): bool   { return $this->role === self::ROLE_EXPERT; }
    public function isBusiness(): bool { return $this->role === self::ROLE_BUSINESS; }
    public function isAdmin(): bool    { return $this->role === self::ROLE_ADMIN; }

    // === Relationships ===
    public function traveler() { return $this->hasOne(Traveler::class); }
    public function expert()   { return $this->hasOne(Expert::class); }
    public function business() { return $this->hasOne(Business::class); }

    public function sentMessages() { return $this->hasMany(Message::class, 'sender_id'); }
    public function receivedMessages() { return $this->hasMany(Message::class, 'receiver_id'); }

    // === Universal Profile Photo Accessor ===
    public function getProfilePhotoUrlAttribute(): string
    {
        // Determine role → delegate to correct model
        return match ($this->role) {
            self::ROLE_TRAVELER => $this->traveler?->profile_photo_url
                ?? asset('data/images/defaults/traveler.png'),

            self::ROLE_EXPERT => $this->expert?->profile_photo_url
                ?? asset('data/images/defaults/expert.png'),

            self::ROLE_BUSINESS => $this->business?->profile_photo_url
                ?? asset('data/images/defaults/business.png'),

            default => asset('data/images/defaults/traveler.png'),
        };
    }
}
