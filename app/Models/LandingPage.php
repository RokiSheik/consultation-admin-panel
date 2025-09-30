<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'slug',
        'section1_image',
        'section1_title',
        'section1_bullets',
        'section1_regular_price',
        'section1_offer_price',
        'section1_registration_text',
        'section2_description',
        'section2_class_details',
        'section3_submit_text',
        'section4_terms_title',
        'section4_terms_bullets',
    ];

    protected $casts = [
        'section1_bullets' => 'array',
        'section2_class_details' => 'array',
        'section4_terms_bullets' => 'array',
    ];
}
