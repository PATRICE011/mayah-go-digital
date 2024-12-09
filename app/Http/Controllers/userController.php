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
use App\Models\Wishlist;
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
    
        // Pass the products, cartCount, and wishlistCount to the view
        return view('home.shop', compact('products', 'cartCount', 'wishlistCount'));
    }
    

    public function details()
    {
        return view('home.details');
    }

    public function cart()
    {
        // Check if the user is authenticated
        $user = Auth::user();

        // If the user is not authenticated, redirect to the login page
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in to view your cart.');
        }

        // If the user is authenticated, get their cart
        $cart = Cart::where('user_id', $user->id)->first();

        // If the cart exists, get the items, otherwise, return an empty collection
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
        } else {
            $cartItems = collect(); // Empty collection if no cart exists
        }

        // Return the view with the cart items
        return view('home.cart', ['cartItems' => $cartItems]);
    }



    public function wishlist()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in to view your cart.');
        }
        $wishlistItems = Wishlist::where('user_id', $user->id)->with('product')->get();
        return view('home.wishlist', compact('wishlistItems'));
    }
    public function addToWishlist($productId)
    {
        $user = Auth::user();
    
        // Ensure the user is logged in
        if (!$user) {
            return redirect()->route('login')->with('message', 'Please log in to add items to your wishlist.');
        }
    
        // Check if the product is already in the user's wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();
    
        if ($existingWishlist) {
            return redirect()->back()->with('message', 'This product is already in your wishlist.');
        }
    
        // Add to the wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);
    
        return back()->with('message', 'Product added to wishlist!');
    }
    
    

    public function removeFromWishlist($wishlistId)
    {
        $wishlist = Wishlist::find($wishlistId);
        $wishlist->delete();

        return back()->with('message', 'Product removed from wishlist.');
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
