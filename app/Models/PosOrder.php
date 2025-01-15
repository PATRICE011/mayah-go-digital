<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    // Define the table associated with the model (optional if using Laravel naming conventions)
    protected $table = 'pos_orders';

    // Mass assignable attributes
    protected $fillable = [
        'order_number',
        'total_amount',
        'cash_paid',
        'change',
        'order_type',
        'status',
    ];

    // Relationships

    /**
     * Get the items for this order.
     */
    public function pos_items()
    {
        return $this->hasMany(PosOrderItem::class, 'order_id', 'id');
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
