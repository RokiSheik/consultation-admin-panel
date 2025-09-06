<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BecomeConsultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'company', 'title', 'email', 'phone',
        'website_link', 'video_link', 'speaking_fees',
        'speaking_experience', 'topics', 'why_join', 'receive_updates'
    ];
}
