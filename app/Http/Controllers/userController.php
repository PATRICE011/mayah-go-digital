<?php

// In app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


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

    public function updateProfile(Request $request)
    {
        // Validate user input
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'otp' => 'required|numeric|digits:6'
        ]);
    
        // Get user data from session
        $userData = $request->session()->get('user_data');
    
        // Check if user data exists in session and validate mobile number
        if (!$userData || $userData['mobile'] !== $request->mobile) {
            return redirect()->back()->with('error', 'Invalid mobile number');
        }
    
        // Verify OTP
        $otp = $userData['otp'];
        if ($otp !== $request->otp) {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    
        // Update user profile in the database using Eloquent
        $user = User::find(Auth::id()); // Find the logged-in user
        if ($user) {
            $user->name = $request->name;  // Assign the new name
            $user->mobile = $request->mobile;  // Assign the new mobile number
            $user->save();  // Save the changes
        } else {
            return redirect()->back()->with('error', 'User not found.');
        }
    
        // Forget OTP session data after successful update
        $request->session()->forget('user_data');
    
        // Redirect to 'my-account' route with success message
        return redirect()->route('my-account')->with('message', 'Profile updated successfully');
    }
    

    public function sendOtp(Request $request)
    {
        // Validate the mobile number
        $request->validate([
            'mobile' => 'required|numeric|digits:10'
        ]);

        $mobile = $request->mobile;
        $otp = rand(100000, 999999);
        $otpCreatedAt = now();

        // Store OTP in session
        $request->session()->put('user_data', [
            'mobile' => $mobile,
            'otp' => $otp,
            'otp_created_at' => $otpCreatedAt,
        ]);

        // Send OTP via API
        try {
            $this->sendOtpToUser($mobile, $otp);
            return redirect()->back()->with('message', 'OTP sent successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function sendOtpToUser($mobile, $otp)
    {
        $apiKey = 'b44a24f27a558fb5290688a7ab25aded';
        $apiUrl = 'https://api.semaphore.co/api/v4/priority';
        $senderName = 'MAYAHSTORE';

        $message = "Your OTP for MAYAHSTORE is $otp. Please enter this code to verify your account. This code is valid for 1 minute.";

        $client = new Client();

        try {
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'apikey' => $apiKey,
                    'number' => $mobile,
                    'message' => $message,
                    'sender' => $senderName,
                ],
                'verify' => false,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            if ($response->getStatusCode() !== 200 || isset($responseBody['error'])) {
                throw new \Exception('Failed to send OTP');
            }
        } catch (\Exception $e) {
            Log::error('Error sending OTP: ' . $e->getMessage());
            throw new \Exception('Failed to send OTP');
        }
    }

    public function verifyOtp(Request $request)
    {
        // Validate OTP
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        // Get user data from session
        $userData = $request->session()->get('user_data');
        if (!$userData || $userData['otp'] !== $request->otp) {
            return redirect()->back()->with('error', 'Invalid OTP');
        }

        // Check if OTP is expired (valid for 1 minute)
        $otpCreatedAt = new \DateTime($userData['otp_created_at']);
        $otpValidityPeriod = 1; // minute
        $currentDateTime = new \DateTime();
        $interval = $currentDateTime->diff($otpCreatedAt);

        if ($interval->i > $otpValidityPeriod || ($interval->i == $otpValidityPeriod && $interval->s > 0)) {
            return redirect()->back()->with('error', 'OTP has expired');
        }

        return redirect()->route('update-profile');
    }
}
