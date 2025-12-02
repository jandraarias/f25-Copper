<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'expert_id',
        'user_id',   // <-- IMPORTANT
        'rating',
        'comment',
    ];

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
