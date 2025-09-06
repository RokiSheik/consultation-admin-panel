<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['affiliator_id', 'amount', 'status'];

     public static function booted()
    {
        static::updated(function ($withdrawal) {
            // Only run when status is changed to 'paid'
            if ($withdrawal->isDirty('status') && $withdrawal->status === 'paid') {
                $affiliator = Affiliator::find($withdrawal->affiliator_id);

                if ($affiliator && $affiliator->balance >= $withdrawal->amount) {
                    $affiliator->balance -= $withdrawal->amount;
                    $affiliator->save();
                } else {
                    // Optional: set status back to pending
                    $withdrawal->status = 'pending';
                    $withdrawal->save();

                    throw new \Exception("Insufficient balance for withdrawal.");
                }
            }
        });
    }

    public function affiliator()
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id', 'id');
    }
}
