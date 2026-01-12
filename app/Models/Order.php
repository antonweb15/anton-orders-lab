<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
        use HasFactory;

        protected $fillable = ['name', 'price'];

        public function scopeLatestFirst($query)
        {
            return $query->select('id', 'name', 'price')->orderBy('id', 'desc');
        }
}
