<?php

namespace App\Http\Controllers;
// use App\Http\User;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
class OtpController extends Controller
{
    //
    public function showOtp(){
        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart ? $cart->items : collect();
        $categories = Category::withCount('products')->get();
        $products = Product::all();
        return view('users.otp',
        [
            'products' => $products,
            'cartItems' => $cartItems,
            'categories' =>  $categories
        ]);
    }

    public function sendOtp($mobile, $otp)
    {
        $apiKey = env('SEMAPHORE_API_KEY');
        $apiUrl = env('SEMAPHORE_API_URL');
        $senderName = env('SEMAPHORE_SENDER_NAME'); // Get sender name from env
    
        // Get the session data to ensure that the mobile number is correct
        $userData = session('user_data');
        if (!$userData || $userData['mobile'] !== $mobile) {
            Log::error('Session data mismatch or user not found in session', ['mobile' => $mobile]);
            throw new \Exception('Session data mismatch or user not found in session. Please try again.');
        }
    
        // No need to update the user in the database, OTP is stored in the session
    
        $client = new Client();
    
        // Message content
        $message = "Your OTP for MAYAHSTORE is $otp. Please enter this code to verify your account. This code is valid for 1 minute.";
    
        try {
            // Log the request payload
            Log::info('Sending SMS request', [
                'endpoint' => $apiUrl,
                'payload' => [
                    'apikey' => $apiKey,
                    'number' => $mobile,
                    'message' => $message,
                    'sender' => $senderName // Include sender name
                ]
            ]);
    
            // Make the API request
            $response = $client->post($apiUrl, [
                'form_params' => [
                    'apikey' => $apiKey,
                    'number' => $mobile,
                    'message' => $message,
                    'sender' => $senderName // Include sender name
                ]
            ]);
    
            // Decode the response
            $responseBody = json_decode($response->getBody(), true);
    
            // Log the response
            Log::info('SMS response', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $responseBody
            ]);
    
            // Check if the response is successful
            if ($response->getStatusCode() !== 200 || isset($responseBody['error'])) {
                Log::error('Semaphore API error', [
                    'status_code' => $response->getStatusCode(),
                    'response_body' => $responseBody
                ]);
                throw new \Exception('Failed to send OTP. Please try again.');
            }
    
            return $responseBody;
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception while sending OTP', ['message' => $e->getMessage()]);
            throw new \Exception('Failed to send OTP due to a system error. Please try again.');
        }
    }
    
    public function verifyOtp(Request $request)
{
    // Validate the OTP input
    $request->validate(['otp' => 'required|string']);

    // Retrieve user data from session
    $userData = $request->session()->get('user_data');

    if ($userData) {
        $otp = $userData['otp'];
        $otpCreatedAt = new \DateTime($userData['otp_created_at']); // Parse stored date-time string
        $otpValidityPeriod = 1; // in minutes

        // Check if OTP is still valid
        $currentDateTime = new \DateTime(); // Current time
        $interval = $currentDateTime->diff($otpCreatedAt);

        // Check if OTP is expired
        if ($interval->i > $otpValidityPeriod || ($interval->i == $otpValidityPeriod && $interval->s > 0)) {
            // OTP is expired
            $request->session()->forget('user_data'); // Clear the session data
            return redirect()->back()->with('error', 'The OTP has expired. Please request a new one.');
        }

        // Check if OTP is correct
        if ($otp == $request->otp) {
            // Create the user
            $user = User::create([
                'name' => $userData['name'],
                'mobile' => $userData['mobile'],
                'address' => $userData['address'],
                'password' => $userData['password'],
                'is_admin' => $userData['is_admin'],
            ]);

            // Clear the OTP from session
            $request->session()->forget('user_data');

            return redirect(route('users.login'))->with('message', 'OTP verified successfully.');
        } else {
            // OTP is invalid or wrong
            return redirect()->back()->with('error', 'Invalid OTP, please try again.');
        }
    } else {
        // Handle missing session data
        return redirect()->back()->with('error', 'Session data not found. Please try again.');
    }
}

    
    
    // request new otp
    public function resendOtp(Request $request)
    {
        // Validate mobile number or other necessary fields
    $request->validate([
        'mobile' => 'required|string'
    ]);

    // Find the user by mobile number
    $user = User::where('mobile', $request->mobile)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Generate a new OTP
    $otp = rand(100000, 999999); // Generate a 6-digit OTP

    // Update user with new OTP and reset attempts
    $user->otp = $otp;
    $user->otp_created_at = now(); // Update the OTP timestamp
    $user->otp_attempts = 0; // Reset the OTP attempts counter
    $user->save();

    // Send the new OTP
    try {
        $this->sendOtp($user->mobile, $otp);
        return redirect()->back()->with('success', 'A new OTP has been sent to your mobile number.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
    }
    }

}