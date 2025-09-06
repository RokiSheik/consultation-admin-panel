<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeakingRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'speaking_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_name',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'whatsapp_number',
        'business_description',
        'speaker_request',
        'speaking_date',
        'event_time',
        'speaking_location',
        'speaking_length',
        'talking_points',
        'keynote_speaker',
        'audience_size',
        'advertise_event',
        'social_media_links',
        'event_content_distribution',
        'hotel_flights_agreement',
        'credentials_agreement',
        'powerpoint_access',
        'raw_footage_agreement',
        'media_presence_agreement',
        'content_approval_agreement',
        'other_engagements',
        'fee_understanding',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'event_content_distribution' => 'array',
    ];
}