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
        'logo_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
