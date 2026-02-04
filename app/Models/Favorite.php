<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'tmdb_id',
        'title',
        'poster_path',
        'overview',
        'release_date',
        'vote_average',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected $casts = [
        'release_date' => 'date',
        'vote_average' => 'decimal:1',
    ];
}
