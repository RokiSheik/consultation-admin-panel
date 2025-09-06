<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{

    protected $fillable = [
        'title',
        'image',
        'video_url',
        'text',
        'large_image',
        'author',
        'date',
        'view',
        'categories',
        'excerpt',
        'body',
        'tags',
    ];
    protected $casts = [
        'author' => 'array',
        'categories' => 'array',
        'body' => 'array',
        'tags' => 'array',
    ];
}
