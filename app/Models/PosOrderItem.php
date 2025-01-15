<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrderItem extends Model
{
    // Define the table associated with the model (optional if using Laravel naming conventions)
    protected $table = 'pos_order_items';

    // Mass assignable attributes
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    // Relationships

    /**
     * Get the parent order for this item.
     */
    public function pos_order()
    {
        return $this->belongsTo(PosOrder::class, 'order_id', 'id');
    }

    /**
     * Get the product associated with this order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
