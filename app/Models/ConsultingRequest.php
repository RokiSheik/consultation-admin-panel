<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultingRequest extends Model
{
     protected $table = 'consulting_requests';

    protected $fillable = [
        'full_name',
        'email_address',
        'phone_number',
        'whatsapp_number',
        'company_name',
        'website_social_media',
        'city_country',
        'age',
        'how_heard_about_us',
        'how_heard_about_us_other',
        'applying_for',
        'applying_for_other',
        'best_describes_you',
        'best_describes_you_other',
        'current_business_description',
        'current_monthly_revenue',
        'business_duration',
        'priority_next_months',
        'priority_next_months_other',
        'biggest_challenges',
        'biggest_challenges_other',
        'stopped_fixing_challenges',
        'specific_outcome',
        'specific_outcome_other',
        'commitment_level',
        'willing_to_invest',
        'investment_budget',
        'book_discovery_call',
        'specific_request',
    ];
}
