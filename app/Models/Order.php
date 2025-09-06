<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'service_name',
        'package_type',
        'total_amount',
        'order_date',
        'status',
        'transaction_id',
        'affiliator_id',
    ];
    protected $casts = [
        'total_amount' => 'float', // Or 'decimal:2' if you prefer
        // ... other casts
    ];

    protected $primaryKey = 'order_id';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function services()
    {
        return $this->hasManyThrough(
            Service::class,             // Final target model
            SubscriptionPackage::class, // Intermediate model
            'package_id',               // Foreign key on SubscriptionPackage (linked to OrderItem)
            'service_id',               // Foreign key on Service (linked to SubscriptionPackage)
            'order_id',                 // Local key on Order (linked to OrderItem)
            'service_id'                // Local key on SubscriptionPackage (linked to Service)
        );
    }
    public function subscription_packages()
    {
        return $this->hasManyThrough(
            SubscriptionPackage::class, // Final model (SubscriptionPackage)
            OrderItem::class,           // Intermediate model (OrderItem)
            'order_id',                 // Foreign key on OrderItem (points to Order)
            'package_id',               // Foreign key on SubscriptionPackage (points to OrderItem)
            'order_id',                 // Local key on Order (points to OrderItem)
        );
    }
    
    protected static function booted()
{
    static::updated(function ($order) {
        if (
            $order->isDirty('status') &&
            $order->status === 'completed' &&
            !empty($order->affiliator_id)
        ) {
            // Lookup by custom 8-digit affiliator_id
            $affiliator = \App\Models\Affiliator::where('affiliator_id', $order->affiliator_id)->first();

            if ($affiliator) {
                $commission = $order->total_amount * 0.30;
                $affiliator->balance += $commission;
                $affiliator->save();
            }
        }
    });
}

    public function affiliator()
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id', 'id');
    }





}
