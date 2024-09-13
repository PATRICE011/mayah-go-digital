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

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        // $order = new Order();
        // $order->user_id = $user->id;
        // $order->total_price = $cartItems->sum(fn($item) => $item->product->product_price * $item->quantity);
        // $order->status = 'Pending'; 
        // $order->save();
        
        return view('home.checkout', compact('cartItems')); 
    }

    public function processCheckout(Request $request)
    {
        // ===== FETCH USER NAME INSTEAD IN CHECKOUT PAGE ====
         // Validate the request
         $user = Auth::user();
         $request->validate([
            'paymentMethod' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'mobile' => 'nullable|string',
            'terms' => 'required|accepted',
        ]);

        // Fetch cart items
        $cartItems = Cart::where('user_id', $user->id)->get();

        // Calculate total amount
        $totalAmount = $cartItems->sum(function($item) {
            return $item->product->product_price * $item->quantity;
        });

        // Create an order
        $order = Order::create([
            'user_id' =>$user->id,
            'total_amount' => $totalAmount,
            'payment_method' => $request->input('paymentMethod'),
            'status' => 'Pending', // or other status as needed
        ]);

        // Store order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->product_price,
            ]);
        }

        // Optionally, clear the cart
        CartItem::where('user_id',$user->id)->delete();

        // Redirect with success message
        return redirect()->route('checkout.form')->with('success', 'Your order has been placed successfully!');
    }
    
    // update
    public function updateQuantity(Request $request)
    {
        $cartItem = CartItem::find($request->id);
        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Item not found']);
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
}
