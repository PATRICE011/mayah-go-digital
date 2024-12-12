<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;


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


    public function details($id)
    {
        // Retrieve the product using the DB query builder
        $product = DB::table('products')->where('id', $id)->first();
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


        // Pass the product to the view
        return view('home.details', compact('product', 'cartCount', 'wishlistCount'));
    }


    public function checkout()
    {
        return view('home.checkout');
    }


    public function orderDetails($orderId)
    {
        // Fetch the main order and its details
        $order = DB::table('orders')
            ->where('orders.id', $orderId)
            ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
            ->select(
                'orders.id as order_id',
                'orderdetails.order_id_custom',
                'orders.status',
                'orderdetails.payment_method',
                'orderdetails.total_amount',
                'orders.created_at'
            )
            ->first();
    
        // Check if the order exists
        if (!$order) {
            abort(404, 'Order not found.');
        }
    
        // Enforce payment-first logic:
        // If the order is paid, ensure the status is always 'pending'
        if ($order->status === 'paid') {
            $order->status = 'pending';
        }
    
        // Dynamically set the payment status (assume 'Paid' for all orders)
        $order->payment_status = 'Paid';
    
        // Fetch all items in the order
        $orderItems = DB::table('order_items')
            ->where('order_id', $orderId)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price'
            )
            ->get();
    
        return view('home.orderdetails', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }
    

    public function dashboard()
    {
        $user = Auth::user(); // Assuming `Auth::user()` is retrieving from the `users_area` table
        $cartCount = 0;
        $wishlistCount = 0;
        $orders = [];

        if ($user) {
            // Fetch cart item count
            $cartId = DB::table('carts')->where('user_id', $user->id)->value('id');
            if ($cartId) {
                $cartCount = DB::table('cart_items')->where('cart_id', $cartId)->sum('quantity');
            }

            // Fetch wishlist count
            $wishlistCount = DB::table('wishlists')->where('user_id', $user->id)->count();

            // Fetch orders for the user
            $orders = DB::table('orders')
                ->where('user_id', $user->id)
                ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id') // Join with orderdetails
                ->select(
                    'orders.id as order_id',
                    'orders.status',
                    'orderdetails.order_id_custom',
                    'orderdetails.total_amount',
                    'orders.created_at'
                )
                ->orderBy('orders.created_at', 'desc')
                ->get();
        }

        return view('home.myaccount', [
            'activeSection' => 'dashboard',
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
            'orders' => $orders, // Pass the orders to the Blade view
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
