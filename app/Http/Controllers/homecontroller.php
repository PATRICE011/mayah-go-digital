<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        Auth::logout(); // Log out the user

        // Clear the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
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
     
        return view('home.index',compact('cartCount', 'wishlistCount'));
    }

    // ==== AUTHENTICATED ROUTE =====
    public function home()
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
    
        return view('home.index', compact('cartCount', 'wishlistCount'));
    }
    
    

    

}
