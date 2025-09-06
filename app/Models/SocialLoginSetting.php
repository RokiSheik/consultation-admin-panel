<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLoginSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'facebook_app_id',
        'facebook_app_secret',
        'google_client_id',
        'google_client_secret',
    ];
}
