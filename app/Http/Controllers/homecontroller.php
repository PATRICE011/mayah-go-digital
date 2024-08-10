<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class homecontroller extends Controller
{
    public function index(){
        $products = Product::all();
        $cart = Cart::where('user_id', auth()->id())->first();
        $cartItems = $cart ? $cart->items : collect();
    
        return view('home.index', [
            'products' => $products,
            'cartItems' => $cartItems 
        ]);
    }
    
}
