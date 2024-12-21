<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class userController extends Controller
{

    public function shop(Request $request)
    {
        // 1. Fetch all categories (for sidebar, etc.)
        $categories = DB::table('categories')->get();
    
        // 2. Build the initial query
        $query = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.category_name');
    
        // 3. Filter by categories if provided
        if ($request->has('categories')) {
            $selectedCategories = $request->categories;  // e.g., array of category names
            $query->whereIn('categories.category_name', $selectedCategories);
        }
    
        // 4. Filter by search if provided
        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where('products.product_name', 'LIKE', "%{$keyword}%");
        }
    
        // 5. Execute the query
        $products = $query->get();
        $totalProducts = $products->count();
    
        // 6. User-related data
        $user = Auth::user();
        $cartCount = 0;
        $wishlistCount = 0;
    
        if ($user) {
            $cartId = DB::table('carts')
                ->where('user_id', $user->id)
                ->value('id');
    
            if ($cartId) {
                $cartCount = DB::table('cart_items')
                    ->where('cart_id', $cartId)
                    ->sum('quantity');
            }
    
            $wishlistCount = DB::table('wishlists')
                ->where('user_id', $user->id)
                ->count();
        }
    
        // 7. If it's an AJAX request, return partial HTML
        if ($request->ajax()) {
            // Render the partial `home.partials.product_grid` with the filtered $products
            $html = view('home.partials.product_grid', compact('products'))->render();
    
            return response()->json([
                'products' => $html,
                'totalProducts' => $totalProducts,
            ]);
        }
    
        // 8. Otherwise, return the full view (shop page)
        return view('home.shop', compact(
            'products',
            'categories',
            'totalProducts',
            'cartCount',
            'wishlistCount'
        ));
    }
    

    // DETAILS START
    public function details($id, Request $request)
{
    // Retrieve the product
    $product = DB::table('products')->where('id', $id)->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $user = Auth::user();
    $cartCount = 0;
    $wishlistCount = 0;

    if ($user) {
        // Get the total cart count
        $cartId = DB::table('carts')
            ->where('user_id', $user->id)
            ->value('id');

        if ($cartId) {
            $cartCount = DB::table('cart_items')
                ->where('cart_id', $cartId)
                ->sum('quantity');
        }

        // Get the wishlist count
        $wishlistCount = DB::table('wishlists')
            ->where('user_id', $user->id)
            ->count();
    }

    // Check if the request expects JSON (e.g., AJAX)
    if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
            'product' => $product,
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount,
        ]);
    }

    // Default to returning the view if it's a regular GET request
    return view('home.details', compact('product', 'cartCount', 'wishlistCount'));
}



    // DETAILS END

    public function checkout()
    {
        return view('home.checkout');
    }

    public function invoice($orderId)
    {
        $order = DB::table('orders')
            ->where('orders.id', $orderId)
            ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id') // Join categories
            ->select(
                'orders.id as order_id',
                'orderdetails.order_id_custom as invoice_number',
                'orderdetails.total_amount',
                'orders.created_at as order_date',
                'products.product_name',
                'categories.category_name as category_name', // Fetch the category name
                'order_items.quantity',
                'order_items.price',
                DB::raw('order_items.quantity * order_items.price as amount')
            )
            ->get();

        $customer = Auth::user();

        return view('home.invoice', [
            'order' => $order,
            'customer' => $customer,
        ]);
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

    // MYACCOUNT
    public function dashboard(Request $request)
{
    $user = Auth::user();
    $cartCount = 0;
    $wishlistCount = 0;
    $orders = [];
    $orderDetails = null;
    $orderItems = [];

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
            ->where('orders.user_id', $user->id)
            ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id') // Join with orderdetails
            ->join('order_items', 'orders.id', '=', 'order_items.order_id') // Join with order_items
            ->select(
                'orders.id as order_id',
                'orders.status',
                'orderdetails.order_id_custom',
                'orders.created_at',
                DB::raw('SUM(order_items.quantity * order_items.price) as subtotal') // Compute subtotal
            )
            ->groupBy('orders.id', 'orders.status', 'orderdetails.order_id_custom', 'orders.created_at') // Group by order fields
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // Check if the request is for a specific order's details
        if ($request->has('order_id')) {
            $orderDetails = DB::table('orders')
                ->where('orders.id', $request->order_id)
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

            $orderItems = DB::table('order_items')
                ->where('order_id', $request->order_id)
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('products.product_name', 'order_items.quantity', 'order_items.price')
                ->get();
        }
    }

         // Set the active_tab session if not already set
    if (!$request->session()->has('active_tab')) {
        $request->session()->put('active_tab', 'dashboard'); 
    }

    return view('home.myaccount', [
        'cartCount' => $cartCount,
        'wishlistCount' => $wishlistCount,
        'orders' => $orders,
        'orderDetails' => $orderDetails,
        'orderItems' => $orderItems,
        'user' => $user,
    ]);
}

public function orderDetails($orderId)
{
    // Fetch the order details from the database
    $order = DB::table('orders')
        ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
        ->where('orders.id', $orderId)
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
        return response()->json(['message' => 'Order not found.'], 404);
    }

    // Dynamically set the payment status
    $order->payment_status = $order->status === 'paid' ? 'Paid' : 'Unpaid';

    // Fetch all items in the order
    $orderItems = DB::table('order_items')
        ->where('order_id', $order->order_id)
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->select(
            'products.product_name',
            'order_items.quantity',
            'order_items.price'
        )
        ->get();

    // Return the rendered HTML partial for order details
    return response()->json([
        'html' => view('home.partials.orderdetails', [
            'order' => $order,
            'orderItems' => $orderItems,
        ])->render(),
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

        $totalProducts = DB::table('products')->count();

        $html = '';
        foreach ($products as $product) {
            $stockClass = $product->product_stocks == 0
                ? 'out-of-stock'
                : ($product->product_stocks > 0 && $product->product_stocks < 10 ? 'low-stock' : '');
            $stockMessage = $product->product_stocks == 0
                ? '<div class="stock-status out-of-stock-message">Out of Stock</div>'
                : ($product->product_stocks > 0 && $product->product_stocks < 10
                    ? '<div class="stock-status low-stock-message">Low Stock</div>'
                    : '');

            // Use the same form structure as in the partial
            $html .= '
        <div class="product__item ' . $stockClass . '">
            <div class="product__banner">
                <a href="#" class="product__images">
                    <img src="' . asset('assets/img/' . $product->product_image) . '" alt="' . $product->product_name . '" class="product__img default">
                    <img src="' . asset('assets/img/' . $product->product_image) . '" alt="' . $product->product_name . '" class="product__img hover">
                </a>
                ' . $stockMessage . '
                <div class="product__actions">
                    <a href="' . url('/details') . '" class="action__btn" aria-label="Quick View">
                        <i class="bx bx-expand-horizontal"></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class="bx bx-heart"></i>
                    </a>
                </div>
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
                <form id="addToCartForm-' . $product->id . '" class="add-to-cart-form" data-url="' . route('home.inserttocart') . '">
                    ' . csrf_field() . '
                    <input type="hidden" name="id" value="' . $product->id . '">
                    <button type="button" class="action__btn cart__btn ' . ($product->product_stocks == 0 ? 'disabled' : '') . '" ' . ($product->product_stocks == 0 ? 'disabled' : '') . '>
                        <i class="bx bx-cart-alt"></i>
                    </button>
                </form>
            </div>
        </div>';
        }

        return response()->json([
            'html' => $html,
            'count' => $products->count(),
            'total' => $totalProducts,
        ]);
    }
}
