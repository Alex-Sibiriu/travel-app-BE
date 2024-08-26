<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'starting_date',
        'ending_date',
        'country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
