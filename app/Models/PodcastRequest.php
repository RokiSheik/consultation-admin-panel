<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastRequest extends Model
{
    protected $fillable = [
        'podcast_name',
        'full_name',
        'phone_number',
        'whatsapp_number',
        'email',
        'website_url',
        'social_media_links',
        'podcast_description',
        'request_speaker',
        'interview_mode',
        'reason_for_guest',
        'interview_length',
        'talking_points',
        'average_views',
        'media_presence_agreement',
        'share_raw_footage_agreement',
        'final_approval_agreement',
    ];
}
