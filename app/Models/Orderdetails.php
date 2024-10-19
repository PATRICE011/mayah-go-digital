<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'order_id_custom', 'payment_method', 'total_amount',
    ];

    // Inverse of the One-to-One relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

