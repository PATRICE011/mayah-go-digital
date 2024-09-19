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

        
        return view('home.checkout', compact('cartItems')); 
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email',
            'paymentMethod' => 'required',
            'terms' => 'accepted',
        ]);
        $user = Auth::user();
        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'payment_method' => $validated['paymentMethod'],
            'total_amount' => array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $request->cartItems)),
            'status' => 'pending'
        ]);
    
        // Save cart items to order_items table (assuming this relation exists)
        
        foreach ($request->cartItems as $item) {
            $order->OrderItems()->create([
                
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        // Find the user's cart and delete it along with its cartItems
        $cart = $user->cart; // Assuming each user has a 'cart' relationship
        if ($cart) {
            $cart->items()->delete(); // Delete all cart items
            $cart->delete(); // Delete the cart itself
        }
        return redirect(route("users.usersdashboard"))->with('message', 'Order placed successfully!');
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

    // my orders
    public function viewOrders(){

        $orders = Order::where('user_id', Auth::id())->with('orderItems.product')->get();
        
        return view ('home.myorders', compact('orders'));
    }
}
