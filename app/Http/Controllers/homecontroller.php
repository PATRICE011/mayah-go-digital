<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class homecontroller extends Controller
{
    public function index(){
        $products = Product::all();
        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart ? $cart->items : collect();
        $categories = Category::withCount('products')->get();

        $categories->prepend((object) [
            'slug' => 'all',
            'category_name' => 'Show All',
            'products_count' => $products->count()
        ]);
        
        return view('home.index', [
            'products' => $products,
            'cartItems' => $cartItems,
            'categories' =>  $categories
        ]);
    }
    
}
