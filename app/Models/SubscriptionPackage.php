<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $primaryKey = 'package_id';
    protected $fillable = ['service_id', 'package_name', 'price', 'duration'];

    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = $value === 'monthly' ? 30 : 365;
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'package_id');
    }
    // In the SubscriptionPackage model
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_subscription_package', 'subscription_package_package_id', 'order_order_id');
    }


}
