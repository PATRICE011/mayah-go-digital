<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Inverse of the One-to-Many relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Belongs-to relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

    
