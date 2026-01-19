<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    protected $fillable = [
        'external_id',
        'customer_name',
        'product',
        'quantity',
        'price',
        'status',
    ];
}
