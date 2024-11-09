<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
class SettingsController extends Controller
{
    //
    public function viewSettings()
{
    // Get the orders for the authenticated user with pagination
    $userId = Auth::id(); 
    $cart2 = Cart::where('user_id', $userId)->first(); 

    if ($cart2) {
        $count = CartItem::where('cart_id', $cart2->id)->count(); 
    } else {
        $count = 0; 
    }

    $orders = Order::with(['user', 'orderDetail'])
        ->where('user_id', $userId) // Filter orders by the authenticated user
        ->paginate(10); // Adjust the number of items per page

    return view('home.settings', compact('orders', 'count'));
}


    public function viewMyorders()
    {
        // Get the orders for the authenticated user
        $userId = Auth::id(); 
        $cart2 = Cart::where('user_id', $userId)->first(); 
        
        if ($cart2) {
            $count = CartItem::where('cart_id', $cart2->id)->count(); 
        } else {
            $count = 0; 
        }
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->get()
            ->map(function ($order) {
                // Calculate the total amount by summing up the price * quantity of all items
                $order->total_amount = $order->orderItems->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
                return $order;
            });
    
        // Define the section variable to be used in the Blade template
        $section = 'order-history'; // or 'overview', depending on the logic needed
    
        // Pass the orders and section variable to the view
        return view('home.viewmyorders', compact('orders', 'section','count'));
    }
}
