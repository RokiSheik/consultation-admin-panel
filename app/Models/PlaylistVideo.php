<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistVideo extends Model
{
    protected $fillable = ['course_id', 'title', 'video_url', 'order'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
