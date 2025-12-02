<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'city',
        'website',
        'description',
        'profile_photo_path',  // uploaded file
        'photo_url',           // optional remote seeded image
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Unified Business Profile Photo Accessor
     *
     * Priority:
     * 1. Local uploaded image
     * 2. Remote image URL (photo_url)
     * 3. Default business image
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        $path = $this->profile_photo_path;

        // Remote URL?
        if ($path && str_starts_with($path, 'http')) {
            return $path;
        }

        // Local uploaded file
        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }

        // Seeded remote URL?
        if (!empty($this->photo_url)) {
            return $this->photo_url;
        }

        // Default business avatar
        return asset('storage/images/defaults/business.png');
    }
}
