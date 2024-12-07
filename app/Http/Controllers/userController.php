<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class userController extends Controller
{


    public function shop()
    {
        // Fetch all products and their associated categories
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.category_name')  // select necessary columns
            ->get();

        // Pass the products to the view
        return view('home.shop', compact('products'));
    }

    public function details()
    {
        return view('home.details');
    }

    public function cart()
    {
        return view('home.cart');
    }

    public function wishlist()
    {
        return view('home.wishlist');
    }

    public function checkout()
    {
        return view('home.checkout');
    }

    public function MyAccount()
    {
        return view('home.myaccount');
    }

    public function orderDetails()
    {
        return view('home.orderdetails');
    }

    public function dashboard()
    {
        return view('home.myaccount', ['activeSection' => 'dashboard']);
    }
}
