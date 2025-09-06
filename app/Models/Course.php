<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'image', 'video_url', 'regular_price', 'price', 'rating', 'total_student',
        'author', 'date', 'tags', 'content', 'details'
    ];

    protected $casts = [
        'tags' => 'array',
        'content' => 'array',
    ];

    public function playlistVideos()
    {
        return $this->hasMany(PlaylistVideo::class)->orderBy('order');
    }
}
