<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'expert_id',
        'rating',
        'comment',
    ];

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
