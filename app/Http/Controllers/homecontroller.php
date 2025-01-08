<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    // FOR GUEST
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

        if ($user) {
            $cartId = DB::table('carts')->where('user_id', $user->id)->value('id');
            if ($cartId) {
                $cartCount = DB::table('cart_items')->where('cart_id', $cartId)->sum('quantity');
            }

            $wishlistCount = DB::table('wishlists')->where('user_id', $user->id)->count();
        }

        // Fetch products with their category name using a JOIN query
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.product_name',
                'products.product_image',
                'products.product_price',
                'categories.category_name'
            )
            ->get();
        // Fetch categories directly
        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id') // Include categories without products
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id') // Include products without sales
            ->select(
                'categories.id',
                'categories.category_name',
                'categories.category_image',
                'categories.slug',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as popularity') // Use COALESCE to handle null values
            )
            ->groupBy('categories.id', 'categories.category_name', 'categories.category_image', 'categories.slug')
            ->orderBy('popularity', 'DESC') // Sort by popularity
            ->get();

        return view('home.index', compact('categories', 'cartCount', 'wishlistCount', 'products'));
    }


    // ==== AUTHENTICATED  =====
    public function home()
    {

        // Default cart count and wishlist count to 0
        // Default cart count and wishlist count to 0
        $user = Auth::user();
        $cartCount = 0;
        $wishlistCount = 0;

        if ($user) {
            $cartId = DB::table('carts')->where('user_id', $user->id)->value('id');
            if ($cartId) {
                $cartCount = DB::table('cart_items')->where('cart_id', $cartId)->sum('quantity');
            }

            $wishlistCount = DB::table('wishlists')->where('user_id', $user->id)->count();
        }

        // Fetch products with their category name using a JOIN query
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.product_name',
                'products.product_image',
                'products.product_price',
                'categories.category_name'
            )
            ->get();
        // Fetch categories directly
        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id') // Include categories without products
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id') // Include products without sales
            ->select(
                'categories.id',
                'categories.category_name',
                'categories.category_image',
                'categories.slug',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as popularity') // Use COALESCE to handle null values
            )
            ->groupBy('categories.id', 'categories.category_name', 'categories.category_image', 'categories.slug')
            ->orderBy('popularity', 'DESC') // Sort by popularity
            ->get();


        return view('home.index', compact('categories', 'cartCount', 'wishlistCount', 'products'));
    }
}
