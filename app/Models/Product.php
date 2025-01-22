<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_image',
        'product_description',
        'cart_product_description',
        'product_price',
        'product_stocks',
        'category_id',
    ];

    // One-to-Many relationship with OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Belongs-to relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
