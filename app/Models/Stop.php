<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_id',
        'title',
        'slug',
        'description',
        'day',
        'order',
        'food',
        'place',
        'latitude',
        'longitude',
        'is_visited',
        'rating'
    ];

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }
}
