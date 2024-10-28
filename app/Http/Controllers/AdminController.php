<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    //
    public function index(){
        $products = Product::with('category')->paginate(5);
        return view("admins.index",['products' =>$products]);
    }
    public function showInventory()
    {
        $categories = Category::all(); // Fetch all categories
    // Fetch products with pagination (5 products per page)
        $products = Product::with('category')->paginate(5);

        return view('admins.inventory', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    public function showDashboard(){
        return view("admins.dashboard");
    }

    // public function showOrders(){
    //     return view("admins.orders");
    // }

    public function showView($id){
            // Fetch the order with related user, order details, and order items
        $order = Order::with(['user', 'orderDetail', 'orderItems.product'])
        ->whereHas('orderDetail', function ($query) use ($id) {
            $query->where('order_id_custom', $id);
        })
        ->firstOrFail();
    return view('admins.view', compact('order'));
    }

    // edit invenotry page
    public function edit($id){
        $product = Product::findOrFail($id);
        return view('admins.editv', compact('product'));
    }

    // 
    public function showCategories(){

        // fetch category
        $categories = Category::all();
        return view ('admins.category', compact('categories'));
    }

    // log out
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function onlineOrders()
    {
        // Eager load the related user and order details
        $orders = Order::with(['user', 'orderDetail'])
        ->get();

    return view('admins.orders', compact('orders'));
    }

    public function confirmOrder(Order $order)
{
    $order->update(['status' => 'confirmed']);
    
    // Optionally, redirect back with a success message
    return redirect()->back()->with('message', 'Order has been confirmed.');
}

}
