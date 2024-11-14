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
    // Get the authenticated user's ID and user object
    $userId = Auth::id();
    $user = Auth::user();

    // Get the cart for the user and count the items, if any
    $cart2 = Cart::where('user_id', $userId)->first();
    $count = $cart2 ? CartItem::where('cart_id', $cart2->id)->count() : 0;

    // Get the user's orders with related details and paginate the results
    $orders = Order::with(['user', 'orderDetail'])
        ->where('user_id', $userId)
        ->paginate(10); // Adjust the number of items per page as needed

    return view('home.settings', compact('orders', 'count', 'user'));
}



    public function viewMyorders($section = 'order-history')
{
    $userId = Auth::id();
    $cart2 = Cart::where('user_id', $userId)->first();
    $count = $cart2 ? CartItem::where('cart_id', $cart2->id)->count() : 0;

    // Fetch the latest order with its items and details
    $latestOrder = Order::where('user_id', $userId)
        ->with(['orderItems.product.category', 'orderDetail']) // Include related data
        ->latest()
        ->first();

    // Calculate subtotal and total based on items in the latest order
    $subtotal = $latestOrder ? $latestOrder->orderItems->sum(fn($item) => $item->price * $item->quantity) : 0;
    $total = $subtotal;

    return view('home.viewmyorders', compact('section', 'count', 'latestOrder', 'subtotal', 'total'));
}

}
