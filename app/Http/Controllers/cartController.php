<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class cartController extends Controller
{
    //
    public function addtocart(Request $request)
    {
        $productId = $request->input('id');
        $product = Product::find($productId);
    
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }
    
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
    
        $cartItem = CartItem::where('cart_id', $cart->id)
                             ->where('product_id', $productId)
                             ->first();
    
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1,
                'price' => $product->product_price,  // Store the price when adding a new item
            ]);
        }
    
        return redirect()->back()->with('success', 'Product added to cart');
    }
    

    public function showCart()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
    
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
        } else {
            $cartItems = collect();
        }
    
        return view('home.cartinside', ['cartItems' => $cartItems]);
    }

    // public function checkout()
    // {
        
    //     $user = Auth::user();
    //     $cart = Cart::where('user_id', $user->id)->first();
    //     $cartItems = CartItem::where('cart_id', $cart->id)->get();


        
        
    //     return view('home.checkout', compact('cartItems')); 
    // }

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
    
            // Loop through the cart items and create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }
    
            // Delete the cart and its items after transferring to orders
            $cart->items()->delete(); // Delete cart items
            $cart->delete(); // Delete cart
    
            // Proceed to PayMongo for payment (assuming route exists)
            return redirect(route("cart.pay",['orderId' => $order->id]))->with('message', 'Order placed successfully!');
    
        } else {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }
    }
    
    // update
   // CartController.php
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1', // Ensure quantity is valid
        ]);

        // Find the cart item by ID and update the quantity
        $cartItem = CartItem::find($id);
        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    
    // delete 
    public function destroy($id)
    {
        // Find the cart item by ID
        $cartItem = CartItem::findOrFail($id);

        // Delete the cart item
        $cartItem->delete();

       
        return redirect()->route('users.usersdashboard')->with('message', 'Item removed from cart.');
    }

    // my orders
    public function viewOrders(){

        $orders = Order::where('user_id', Auth::id())->with('orderItems.product')->get();
        
        return view ('home.myorders', compact('orders'));
    }



    
    
}