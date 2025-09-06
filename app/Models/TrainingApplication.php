<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email_address',
        'phone_number',
        'whatsapp_number',
        'city_country',
        'age',
        'current_occupation',
        'current_work_description',
        'current_stage_revenue_structure',
        'average_monthly_income',
        'invested_in_development',
        'investment_details',
        'biggest_goal',
        'top_challenges',
        'program_applying_for',
        'expected_result',
        'why_good_fit',
        'commitment_level',
        'ready_to_invest',
        'expected_budget',
        'how_did_you_hear',
        'additional_info',
    ];
}
