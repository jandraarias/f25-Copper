<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'city',
        'profile_photo_path',
        'bio',
        'expertise',
        'languages',
        'experience_years',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(ExpertReview::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            $path = public_path('storage/' . $this->profile_photo_path);
            if (file_exists($path)) {
                return asset('storage/' . $this->profile_photo_path);
            }
        }

        return asset('storage/images/defaults/expert.png');
    }
}
