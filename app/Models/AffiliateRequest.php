<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Affiliator;

class AffiliateRequest extends Model
{
    protected $fillable = [
        'full_name',
        'email_address',
        'phone_number',
        'whatsapp_number',
        'location',
        'website_social_media_link',
        'primary_audience',
        'followers_subscribers',
        'promotion_platforms',
        'reason_to_join',
        'done_affiliate_marketing',
        'how_heard_about_us',
        'agreement',
        'status',
        'payment_method',
        'payment_method_details', 
    ];

    protected static function booted()
    {
        static::updated(function ($request) {
            if ($request->isDirty('status')) {
                if ($request->status === 'approved') {
                    // Create affiliator if not exists
                    Affiliator::updateOrcreate(
                        ['email' => $request->email_address],
                        [
                            'name' => $request->full_name,
                            'balance' => 0,
                            'payment_method' => $request->payment_method,
                            'payment_method_details' => $request->payment_method_details,
                        ]
                    );
                }

                if ($request->status === 'declined') {
                    Affiliator::where('email', $request->email_address)->delete();
                }
            }
        });
    }
}
