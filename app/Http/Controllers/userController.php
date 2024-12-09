<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;


class userController extends Controller
{

    public function shop()
    {
        $categories = DB::table('categories')->get();
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.category_name')
            ->get();
    
        // Count all products in the database
        $totalProducts = DB::table('products')->count();
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Default cart count and wishlist count to 0
        $cartCount = 0;
        $wishlistCount = 0;
    
        // If the user is logged in, fetch the cart item count and wishlist count
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
    
            // Get the count of products in the user's wishlist
            $wishlistCount = DB::table('wishlists')
                ->where('user_id', $user->id)
                ->count(); // Count the number of products in the wishlist
        }
    
        // Pass the products, categories, total product count, cartCount, and wishlistCount to the view
        return view('home.shop', compact('products', 'categories', 'totalProducts', 'cartCount', 'wishlistCount'));
    }
    

    public function details()
    {
        return view('home.details');
    }


    public function checkout()
    {
        return view('home.checkout');
    }


    public function orderDetails()
    {
        return view('home.orderdetails');
    }

    public function dashboard()
    {
        // Default cart count and wishlist count to 0
        $user = Auth::user();
        $cartCount = 0;
        $wishlistCount = 0;

        // If the user is logged in, fetch the cart item count and wishlist count
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

            // Get the count of products in the user's wishlist
            $wishlistCount = DB::table('wishlists')
                ->where('user_id', $user->id)
                ->count(); // Count the number of products in the wishlist
        }

        // Pass cartCount and wishlistCount to the view
        return view('home.myaccount', [
            'activeSection' => 'dashboard',
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount
        ]);
    }
}
