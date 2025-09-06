<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliator extends Model
{
    protected $fillable = ['name', 'email', 'affiliator_id', 'balance', 'payment_method',          // ✅ added
        'payment_method_details'];

     protected static function booted()
    {
        static::creating(function ($affiliator) {
            // Generate unique 8-digit affiliator ID
            do {
                $affiliatorId = mt_rand(10000000, 99999999);
            } while (self::where('affiliator_id', $affiliatorId)->exists());

            $affiliator->affiliator_id = $affiliatorId;
        });
    }

    // Relations without foreign keys - optional
    public function orders()
    {
        return $this->hasMany(Order::class, 'affiliator_id', 'id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'affiliator_id', 'id');
    }
}
