<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class WishlistController extends Controller
{
    //
    public function wishlist()
    {
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
    
        // If the user is not logged in, redirect to login page
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to log in to view your wishlist.');
        }
    
        // Get the user's wishlist items with associated product data
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with('product') // Ensure products are loaded
            ->get();
    
        // Pass the data to the view
        return view('home.wishlist', compact('wishlistItems', 'cartCount', 'wishlistCount'));
    }
    
    public function addToWishlist(Request $request, $productId)
    {
        $user = Auth::user();
    
        // Ensure the user is logged in
        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }
    
        // Check if the product exists
        $product = DB::table('products')->where('id', $productId)->first();
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
    
        // Check if the product is already in the user's wishlist
        $existingWishlist = DB::table('wishlists')
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();
    
        if ($existingWishlist) {
            return response()->json(['error' => 'This product is already in your wishlist.'], 400);
        }
    
        // Add to the wishlist
        DB::table('wishlists')->insert([
            'user_id' => $user->id,
            'product_id' => $productId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        // Get updated wishlist count
        $wishlistCount = DB::table('wishlists')
            ->where('user_id', $user->id)
            ->count();
    
        return response()->json([
            'message' => 'Product added to wishlist!',
            'wishlistCount' => $wishlistCount,
        ], 200);
    }
    


    public function removeFromWishlist($wishlistId)
    {
        $wishlist = Wishlist::find($wishlistId);
        $wishlist->delete();

        return back()->with('message', 'Product removed from wishlist.');
    }
}
