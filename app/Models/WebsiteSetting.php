<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'website_name', 'site_motto', 'site_icon', 'website_base_color', 'website_base_hover_color',
        'meta_title', 'meta_description', 'meta_keywords', 'meta_image',
        'cookies_content', 'show_cookies_agreement',
        'show_website_popup', 'popup_content',
        'header_custom_script', 'footer_custom_script',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'show_cookies_agreement' => 'boolean',
        'show_website_popup' => 'boolean',
    ];
}
