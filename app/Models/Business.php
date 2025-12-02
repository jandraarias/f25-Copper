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
        'profile_photo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === Accessor ===
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!empty($this->profile_photo_path)) {
            return asset('storage/' . ltrim($this->profile_photo_path, '/'));
        }

        return asset('data/images/defaults/business.png');
    }
}
