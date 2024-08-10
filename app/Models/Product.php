<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'product_image',
        'product_price',
        'product_stocks',
        // 'category_name'
        
    ];
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

}
