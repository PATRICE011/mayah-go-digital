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
    public function viewDashboard()
    {
        $products = Product::all();
        $cart = Cart::where('user_id', Auth::id())->first();
        $categories = Category::withCount('products')->get();
        $cartItems = $cart ? $cart->items : collect();

        // cart count
        $userId = Auth::id();
        $cart2 = Cart::where('user_id', $userId)->first();

        if ($cart2) {
            $count = CartItem::where('cart_id', $cart2->id)->count();
        } else {
            $count = 0;
        }


        $categories->prepend((object) [
            'slug' => 'all',
            'category_name' => 'Show All',
            'products_count' => $products->count()
        ]);

        return view('users.usersdashboard', [
            'products' => $products,
            'cartItems' => $cartItems,
            'categories' =>  $categories,
            'count' => $count
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
        $formFields = $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt(['mobile' => $formFields['mobile'], 'password' => $formFields['password']])) {
            $request->session()->regenerate();

            $user = Auth::user(); // Authenticated user
            $roleId = $user->role_id; // Use role_id to determine redirection



            // Redirect based on role_id
            switch ($roleId) {
                case 1: // Admin
                    return redirect()->route('admins.index')->with('message', 'Login Successful, Welcome Admin!');
                case 2: // Staff
                    return redirect()->route('admins.dashboard')->with('message', 'Login Successful, Welcome Staff!');
                case 3: // Resident/User
                    return redirect()->route('users.usersdashboard')->with('message', 'Login Successful, Welcome User!');
                default:
                    Auth::logout(); // Fallback for unrecognized roles
                    return redirect('/')->withErrors(['error' => 'Unauthorized access.']);
            }
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
                    if (Admin::where('mobile', $value)->exists()) {
                        $fail('The mobile number is already associated with an admin account.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate OTP code and its creation time
        $otp = rand(100000, 999999);
        $otpCreatedAt = new \DateTime(); // Current time

        // Temporarily store user data and OTP creation time in session
        $request->session()->put('user_data', [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_created_at' => $otpCreatedAt->format('Y-m-d H:i:s'), // Store as string
            'is_admin' => $request->is_admin ?? 0,
        ]);

        // Send OTP via Semaphore
        $otpController = new OtpController();
        $otpController->sendOtp($request->mobile, $otp);

        return redirect()->route('users.otp')
            ->with('message', 'Registration successful! Please check your mobile for the OTP.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('name');

        return redirect(route('home.index'))->with('message', ' Logout Successful');
    }
}
