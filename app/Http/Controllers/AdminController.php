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


    public function viewPOSorders(){
        return view("admins.posOrders");
    }
    
    public function showPOSorders(){
        return view("admins.viewposOrders");
    }
    public function viewPOS(Request $request)
{
    $categories = Category::all(); // Fetch all categories
    $selectedCategoryId = $request->get('category_id'); // Get the selected category ID from the request

    // Filter products by the selected category if it exists
    $products = $selectedCategoryId 
        ? Product::where('category_id', $selectedCategoryId)->get() 
        : Product::all(); // Otherwise, show all products

    return view('admins.pos', compact('products', 'categories', 'selectedCategoryId'));
}
}
