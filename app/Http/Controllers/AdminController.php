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

    public function showView(){
        return view("admins.view");
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
        $orders = DB::table('orders')
            ->join('users_area', 'orders.user_id', '=', 'users_area.id')
            ->join('orderdetails', 'orders.id', '=', 'orderdetails.order_id')
            ->select(
                'orderdetails.order_id_custom as order_id',
                'users_area.name as customer',
                'orderdetails.payment_method',
                'orderdetails.total_amount as amount',
                'orders.status',
                'orders.created_at as date'
            )
            ->get();
    
        return view('admins.orders', compact('orders'));
    }
}
