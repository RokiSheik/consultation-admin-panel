<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'paypal_client_id',
        'paypal_client_secret',
        'paypal_sandbox_mode',
        'sslcz_store_id',
        'sslcz_store_password',
        'sslcz_sandbox_mode',
    ];
}
