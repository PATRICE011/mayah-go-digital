<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    //
    public function index(){
        $products = Product::all();
        return view("admins.index",['products' =>$products]);
    }
    public function showInventory(){
        $products = Product::all();
        return view("admins.inventory",['products' =>$products]);
    }
    public function showDashboard(){
        return view("admins.dashboard");
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
}
