<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\Cart;
use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class userController extends Controller
{


    public function shop()
    {
        // Fetch all products and their associated categories
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.category_name')
            ->get();
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Default cart count to 0
        $cartCount = 0;
    
        // If the user is logged in, fetch the cart item count
        if ($user) {
            // Fetch the cart's ID for the authenticated user
            $cartId = DB::table('carts')
                ->where('user_id', $user->id)
                ->value('id'); // Get the cart ID for the current user
    
            // If the cart exists, get the count of items
            if ($cartId) {
                $cartCount = DB::table('cart_items')
                    ->where('cart_id', $cartId)
                    ->sum('quantity'); // Sum the quantity of items in the cart
            }
        }
    
        // Pass the products and cartCount to the view
        return view('home.shop', compact('products', 'cartCount'));
    }

    public function details()
    {
        return view('home.details');
    }

    public function cart()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
    
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
        } else {
            $cartItems = collect();
        }
        return view('home.cart', ['cartItems' => $cartItems]);
    }

    public function wishlist()
    {
        return view('home.wishlist');
    }

    public function checkout()
    {
        return view('home.checkout');
    }

    public function MyAccount()
    {
        return view('home.myaccount');
    }

    public function orderDetails()
    {
        return view('home.orderdetails');
    }

    public function dashboard()
    {
        return view('home.myaccount', ['activeSection' => 'dashboard']);
    }
   
}
