<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        return view('home.index');
    }

    // ==== AUTHENTICATED ROUTE =====
    public function home()
    {
        
        return view('home.index');
    }
    
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
}
