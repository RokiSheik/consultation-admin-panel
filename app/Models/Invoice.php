<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'service',
        'package',
        'amount',
        'email',
        'status',
        'invoice_code',
    ];
}
