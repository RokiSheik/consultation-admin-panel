<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Order;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp',
        'profession',
        'business_type',
        'current_revenue',
        'future_revenue',
        'total_team',
        'landing_title', 
    ];

    // Relationship: Lead has many Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
