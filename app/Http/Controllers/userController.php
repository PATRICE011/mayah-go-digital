<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

    //    ========== UPDATE PROFILE ===========
    public function updateProfile(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string', // Valid mobile number format
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Generate OTP and save it to the database
        $otp = rand(100000, 999999); // Generate a 6-digit OTP

        // Using DB facade to update the user's OTP and timestamp
        DB::table('users_area')  // Assuming you have a 'users' table
            ->where('id', $user->id)
            ->update([
                'otp' => $otp,
                'otp_created_at' => now(),
            ]);

        // Send OTP to the user's mobile via Semaphore
        $this->sendOtp($user->mobile, $otp);

        // Return success response for AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        // Otherwise, fall back to redirect
        return back()->with('message', 'Profile updated successfully!');
    }


    // OTP verification
    public function verifyOtp(Request $request)
    {
        // Validate OTP
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();

        // Get the user's OTP and OTP creation time from the database
        $userData = DB::table('users_area')->where('id', $user->id)->first();

        // Check if OTP is correct and within validity period (5 minutes)
        if ($userData && $userData->otp == $request->otp && Carbon::parse($userData->otp_created_at)->addMinutes(5)->isAfter(now())) {
            // OTP is valid, update the user's profile in the database
            DB::table('users_area')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'otp' => null, // Clear OTP after successful verification
                    'otp_created_at' => null, // Clear OTP timestamp
                ]);

            return back()->with('message', 'Profile updated successfully!');
        }

        // If OTP is invalid or expired
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    // Function to send OTP via Semaphore
    private function sendOtp($mobile, $otp)
    {
        Log::info("Sending OTP {$otp} to user mobile: {$mobile}");

        // Semaphore API integration (replace with your API credentials)
        $apiKey = env('SEMAPHORE_API_KEY');  // Store your Semaphore API Key in .env
        $senderName = env('SEMAPHORE_SENDER'); // Your Semaphore sender name (if any)

        // Use the Http facade with 'verify' => false to bypass SSL verification
        $response = Http::withOptions([
            'verify' => false,  // Disable SSL verification
        ])->post('https://api.semaphore.co/api/v4/priority', [
            'apikey' => $apiKey,
            'to' => $mobile,
            'from' => $senderName,
            'message' => "Your OTP code is: {$otp}",
        ]);

        // Check for successful SMS delivery
        if ($response->successful()) {
            Log::info('OTP sent successfully via Semaphore');
        } else {
            Log::error('Failed to send OTP via Semaphore');
        }
    }
}
