<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class cartController extends Controller
{
    //
    public function addtocart(Request $request)
{
    $productId = $request->input('id');
    $quantity = $request->input('quantity', 1); // Default to 1 if not provided
    $product = Product::find($productId);

    // Check if the product exists
    if (!$product) {
        return redirect()->back()->with('error', 'Product not found');
    }

    $user = Auth::user();
    $cart = Cart::firstOrCreate(['user_id' => $user->id]);

    // Check if the product is already in the cart
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('product_id', $productId)
        ->first();

    // If the product is already in the cart, display a toastr message
    if ($cartItem) {
        return redirect()->back()->with('error', 'This product is already in your cart.');
    }

    // If the product is not in the cart, add it
    CartItem::create([
        'cart_id' => $cart->id,
        'product_id' => $productId,
        'quantity' => $quantity,
        'price' => $product->product_price, // Store the price when adding a new item
    ]);

    return redirect()->back()->with('message', 'Product added to cart.');
}

    public function cart()
    {
        // Check if the user is authenticated
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

        // If the user is not logged in, redirect to the login page
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

        // Pass the data to the view
        return view('home.cart', [
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
            'wishlistCount' => $wishlistCount
        ]);
    }



    public function processCheckout(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Find the user's cart
        $cart = Cart::where('user_id', $user->id)->first();
    
        // Validate cart existence and items
        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }
    
        // Validate quantities input
        if (!$request->has('quantities') || !is_array($request->quantities)) {
            Log::error('Quantities input is missing or invalid.', ['quantities' => $request->quantities]);
            return redirect()->back()->with('error', 'Invalid quantities provided.');
        }
    
        // Create a new order for the user
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending', // Initial status before payment
        ]);
    
        if (!$order) {
            Log::error('Order creation failed for user ID: ' . $user->id);
            return redirect()->back()->with('error', 'Failed to create order.');
        }
    
        // Debug quantities
        Log::info('Quantities received:', $request->quantities);
    
        // Loop through cart items and create order items
        foreach ($cart->items as $cartItem) {
            $updatedQuantity = array_key_exists($cartItem->id, $request->quantities)
                ? $request->quantities[$cartItem->id]
                : $cartItem->quantity;
    
            // Ensure the quantity is within allowable range
            $updatedQuantity = max(1, min($updatedQuantity, $cartItem->product->product_stocks));
    
            // Debug order item data
            Log::info('Creating OrderItem:', [
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $updatedQuantity,
                'price' => $cartItem->product->product_price,
            ]);
    
            // Create the order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $updatedQuantity,
                'price' => $cartItem->product->product_price,
            ]);
        }
    
        // Redirect to payment page where the cart data will be transferred to the order_items table
        return redirect(route("cart.pay", ['orderId' => $order->id]))->with('message', 'Order placed successfully!');
    }
    


    // delete 
    public function destroy($id)
    {
        // Find the cart item by ID
        $cartItem = CartItem::findOrFail($id);

        // Delete the cart item
        $cartItem->delete();


        return back()->with('message', 'Item removed from cart.');
    }

    public function updateQuantity(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'cart_item_id' => 'required|exists:cart_items,id', 
        'quantity' => 'required|integer|min:1',            
    ]);

    $cartItem = CartItem::find($validated['cart_item_id']);

    // Check if the product exists and has enough stock
    $product = $cartItem->product; // Assuming a `product` relationship in CartItem
    if ($validated['quantity'] > $product->product_stocks) {
        return response()->json(['success' => false, 'message' => 'Quantity exceeds available stock'], 400);
    }

    // Update the quantity
    $cartItem->quantity = $validated['quantity'];
    $cartItem->save();

    return response()->json(['success' => true, 'message' => 'Quantity updated successfully']);
}




}
