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

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            $path = public_path('storage/' . $this->profile_photo_path);
            if (file_exists($path)) {
                return asset('storage/' . $this->profile_photo_path);
            }
        }

        return asset('storage/images/defaults/business.png');
    }
}
