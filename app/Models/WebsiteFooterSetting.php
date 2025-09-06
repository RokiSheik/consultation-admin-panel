<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteFooterSetting extends Model
{
    use HasFactory;

    protected $table = 'website_footer_settings';

    protected $fillable = [
        'footer_logo',
        'about_description',
        'playstore_link',
        'applestore_link',
        'office_address',
        'phone',
        'email',
        'footer_links',
        'copyright_text',
        'social_links',
        'payment_method_image',
    ];

    protected $casts = [
        'footer_links' => 'array',
        'social_links' => 'array',
    ];
}
