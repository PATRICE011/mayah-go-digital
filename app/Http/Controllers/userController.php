<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OtpController;
use App\Models\User;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;


class userController extends Controller
{
  

    public function shop()
    {
        return view('home.shop');
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
