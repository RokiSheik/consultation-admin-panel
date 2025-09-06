<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteHeaderSetting extends Model
{
    use HasFactory;
    protected $table = 'website_header_settings';

    protected $fillable = [
        'header_logo',
        'enable_sticky_header',
        'header_menu',
    ];

    protected $casts = [
        'header_menu' => 'array', // Ensures it's always an array
    ];
    
    
}
