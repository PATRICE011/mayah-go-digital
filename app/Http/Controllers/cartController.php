<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class cartController extends Controller
{
    //
    public function addtocart(Request $request)
    {
        $productId = $request->input('id');
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
            // Instead of adding more items, we show a toastr message
            return redirect()->back()->with('error', 'This product is already in your cart.');
        }
    
        // If the product is not in the cart, add it
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => 1,
            'price' => $product->product_price,  // Store the price when adding a new item
        ]);
    
        // Redirect back with a success message
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

    // If the cart exists and has items
    if ($cart && $cart->items()->count() > 0) {

        // Create a new order for the user
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending', // Initial status before payment
        ]);

        // Loop through cart items and create order items
        foreach ($cart->items as $cartItem) {
            $updatedQuantity = isset($request->quantities[$cartItem->id]) ? $request->quantities[$cartItem->id] : $cartItem->quantity;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' =>  $updatedQuantity,
                'price' => $cartItem->price,
            ]);
        }

        // Don't delete the cart items here, we'll handle it later in the payment process

        // Redirect to payment page where the cart data will be transferred to the order_items table
        return redirect(route("cart.pay", ['orderId' => $order->id]))->with('message', 'Order placed successfully!');
    } else {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }
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

    // my orders



}
