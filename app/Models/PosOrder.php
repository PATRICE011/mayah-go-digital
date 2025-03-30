<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'ip_address', 'user_agent', 'total_amount', 'cash_paid', 'change', 'status'
    ];

    public function items()
    {
        return $this->hasMany(PosOrderItem::class, 'pos_order_id');
    }
}
