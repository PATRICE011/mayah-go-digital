<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\otpController;
use App\Models\User;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\Category;

class UserController extends Controller
{
    public function viewDashboard()
    {
        $products = Product::all();
        $cart = Cart::where('user_id', Auth::id())->first();
        $categories = Category::withCount('products')->get();
        $cartItems = $cart ? $cart->items : collect();
        
        $categories->prepend((object) [
            'slug' => 'all',
            'category_name' => 'Show All',
            'products_count' => $products->count()
        ]);

        return view('users.usersdashboard', [
            'products' => $products,
            'cartItems' => $cartItems,
            'categories' =>  $categories
        ]);
    }

    public function getRegister()
    {
        $products = Product::all();
        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart ? $cart->items : collect();
        $categories = Category::withCount('products')->get();

        $categories->prepend((object) [
            'slug' => 'all',
            'category_name' => 'Show All',
            'products_count' => $products->count()
        ]);

        return view('users.register', [
            'products' => $products,
            'cartItems' => $cartItems,
            'categories' =>  $categories
        ]);
    }

    public function getLogin()
    {
        $products = Product::all();
        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart ? $cart->items : collect();
        $categories = Category::withCount('products')->get();

        $categories->prepend((object) [
            'slug' => 'all',
            'category_name' => 'Show All',
            'products_count' => $products->count()
        ]);
        
        return view('users.login', [
            'products' => $products,
            'cartItems' => $cartItems,
            'categories' =>  $categories
        ]);
    }

    public function postLogin(Request $request)
    {
        // Validate the login credentials
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);

        $mobile = $request->mobile;
        $password = $request->password;

        // Check if the credentials match an admin
        $admin = Admin::where('mobile', $mobile)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            return redirect()->intended(route('admins.index'));
        }

        // Check if the credentials match a user
        $user = User::where('mobile', $mobile)->first();
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(route('home.index'));
        }

        // If login fails, return back with an error
        return back()->withErrors([
            'mobile' => 'The provided credentials do not match our records.',
        ]);
    }



    public function postRegister(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'mobile' => [
            'required',
            'string',
            'max:15',
            'unique:users_area',
            function ($attribute, $value, $fail) {
                // Check if the mobile number exists in the admins table
                if (\App\Models\Admin::where('mobile', $value)->exists()) {
                    $fail('The mobile number is already associated with an admin account.');
                }
            },
        ],
        // 'address' => 'required|string|max:500',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Generate OTP code
    // $otp = rand(100000, 999999);

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'mobile' => $request->mobile,
        'address' => $request->address,
        'password' => Hash::make($request->password),
        // 'otp' => $otp,
        'is_admin' => $request->is_admin ?? 0,
    ]);

    // Send OTP via Semaphore
    // $otpController = new otpController();
    // $otpController->sendOtp($user->mobile, $otp);

    return redirect()->route('users.login');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('name');

        return redirect(route('home.index'));
    }
}

