<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'stripe_id',
        'amount',
        'currency',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
