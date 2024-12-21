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
    // public function index()
    // {
    //     $products = Product::with('category')->paginate(5);
    //     return view("admins.index", ['products' => $products]);
    // }
    // public function showInventory()
    // {
    //     $categories = Category::all(); // Fetch all categories
    //     // Fetch products with pagination (5 products per page)
    //     $products = Product::with('category')->paginate(5);

    //     return view('admins.inventory', [
    //         'products' => $products,
    //         'categories' => $categories
    //     ]);
    // }

    // public function showDashboard()
    // {
    //     return view("admins.dashboard");
    // }

    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);
    //     return view('admins.editv', compact('product'));
    // }

    // // 
    // public function showCategories()
    // {

    //     // fetch category
    //     $categories = Category::all();
    //     return view('admins.category', compact('categories'));
    // }

    // log out
    // public function logout(Request $request)
    // {
    //     Auth::guard('web')->logout(); // Use the 'web' guard here
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/'); // Redirect to the desired location
    // }

    // public function showView($id)
    // {
    //     // Fetch the order with related user, order details, and order items
    //     $order = Order::with(['user', 'orderDetail', 'orderItems.product'])
    //         ->whereHas('orderDetail', function ($query) use ($id) {
    //             $query->where('order_id_custom', $id);
    //         })
    //         ->firstOrFail();

    //     return view('admins.view', compact('order'));
    // }

    // public function onlineOrders()
    // {
    //     // Eager load the related user and order details
    //     $orders = Order::with(['user', 'orderDetail'])
    //         ->get();

    //     return view('admins.orders', compact('orders'));
    // }


    // public function viewPOSorders()
    // {
    //     return view("admins.posOrders");
    // }

    // public function showPOSorders()
    // {
    //     return view("admins.viewposOrders");
    // }

    // public function viewPOS(Request $request)
    // {
    //     $categories = Category::all(); // Fetch all categories
    //     $selectedCategoryId = $request->get('category_id'); // Get the selected category ID from the request

    //     // Filter products by the selected category if it exists
    //     $products = $selectedCategoryId
    //         ? Product::where('category_id', $selectedCategoryId)->get()
    //         : Product::all(); // Otherwise, show all products

    //     return view('admins.pos', compact('products', 'categories', 'selectedCategoryId'));
    // }

    public function index()
    {
        return view("admins.index"); 
    }

    public function admindashboard()
    {
        return view("admins.dashboard");
    }

    public function postLogin(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            return redirect()->route('admins.dashboard'); // Redirect admins
        } else {
            return redirect('/home'); // Redirect normal users
        }
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
}


    public function adminproducts()
    {
        return view("admins.adminproducts");
    }

    public function admincategories()
    {
        return view("admins.admincategories");
    }

    public function adminstocks()
    {
        return view("admins.adminstocks");
    }

    public function adminposorders()
    {
        return view("admins.adminposorders");
    }

    public function adminonlineorders()
    {
        return view("admins.adminonlineorders");
    }

    public function adminrefund()
    {
        return view("admins.adminrefund");
    }

    public function adminadministrators()
    {
        return view("admins.adminadministrators");
    }

    public function admincustomers()
    {
        return view("admins.admincustomers");
    }

    public function adminemployee()
    {
        return view("admins.adminemployee");
    }

    public function adminaudit()
    {
        return view("admins.adminaudit");
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        

        return redirect('/')->with('message', 'Logout Successful');
    }
}
