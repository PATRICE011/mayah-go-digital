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
class otpController extends Controller
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

    // Check if user exists
    $user = User::where('mobile', $mobile)->first();
    if (!$user) {
        Log::error('User not found', ['mobile' => $mobile]);
        throw new \Exception('User not found. Please check the mobile number and try again.');
    }

    // Update user with OTP
    $user->otp = $otp;
    $user->otp_created_at = now(); // Store the current timestamp
    $user->otp_attempts = 0; // Reset the OTP attempts counter
    $user->save();

    $client = new Client();

    // Message content
    $message = "Your OTP for MAYAHSTORE is $otp. Please enter this code to verify your account. This code is valid for 5 minutes.";

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
    $request->validate(['otp' => 'string']);

    // Find the user by OTP
    $user = User::where('otp', $request->otp)->first();

    if ($user) {
        // Mark the user as verified by clearing the OTP
        $user->otp = null;
        $user->save();

        // Store success message in session and redirect to home page
        return redirect()->route('users.login')->with('success', 'OTP verified successfully.');
    } else {
        // Increment OTP attempts if user is not found
        $user = User::where('mobile', $request->mobile)->first();

        if ($user) {
            $user->otp_attempts += 1;
            $user->save();

            if ($user->otp_attempts >= 3) {
                // Handle lockout after 3 failed attempts
                return redirect()->back()->with('error', 'Too many invalid attempts. Please request a new OTP.');
            }
        }

        // Handle invalid OTP and redirect back with an error message
        return redirect()->back()->with('error', 'Invalid OTP, please try again.');
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