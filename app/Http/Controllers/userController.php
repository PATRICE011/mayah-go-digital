<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
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

    public function about()
    {
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

        return view('home.about', [
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount
        ]);          
    }

    public function privacypolicy()
    {
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

        return view('home.privacypolicy', [
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount
        ]);          
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
    public function filterProducts(Request $request)
    {
        $categories = $request->input('categories', []);
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.category_name')
            ->when(!empty($categories), function ($query) use ($categories) {
                $query->whereIn('categories.category_name', $categories);
            })
            ->get();
        // Count total products in the database
        $totalProducts = DB::table('products')->count();
        // Generate the HTML for filtered products
        $html = '';
        foreach ($products as $product) {
            $html .= '
                <div class="product__item">
                    <div class="product__banner">
                        <a href="#" class="product__images">
                            <img src="' . asset('assets/img/' . $product->product_image) . '" alt="' . $product->product_name . '" class="product__img default">
                            <img src="' . asset('assets/img/' . $product->product_image) . '" alt="' . $product->product_name . '" class="product__img hover">
                        </a>
                        <div class="product__actions">
                            <a href="' . url('/details') . '" class="action__btn" aria-label="Quick View">
                                <i class="bx bx-expand-horizontal"></i>
                            </a>
                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class="bx bx-heart"></i>
                            </a>
                        </div>
                        <div class="product__badge light-pink">Hot</div>
                    </div>
                    <div class="product__content">
                        <span class="product__category">' . $product->category_name . '</span>
                        <a href="details.html">
                            <h3 class="product__title">' . $product->product_name . '</h3>
                        </a>
                        <div class="product__price flex">
                            <span class="new__price">₱ ' . number_format($product->product_price, 2) . '</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>
                        
                        <form action="' . route('home.inserttocart') . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            <input type="hidden" name="id" value="' . $product->id . '">
                            <button type="submit" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class="bx bx-cart-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            ';
        }
        return response()->json(['html' => $html, 'count' => $products->count(), 'total' => $totalProducts]);
    }
}
