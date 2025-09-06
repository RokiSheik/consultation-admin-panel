<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookConsultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'company', 'title', 'email', 'phone',
        'interested_speaker', 'event_name', 'event_date', 'event_location',
        'event_budget', 'event_website', 'additional_info', 'receive_updates'
    ];
}
