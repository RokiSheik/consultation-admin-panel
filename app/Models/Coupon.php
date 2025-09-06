<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount_amount', 'discount_type', 'expires_at', 'applies_to'];

    protected $casts = [
        'applies_to' => 'array', // Cast JSON field to array
        'expires_at' => 'date',
    ];
}
