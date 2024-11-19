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

class otpController extends Controller
{
    //
    public function showOtp()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart ? $cart->items : collect();
        $categories = Category::withCount('products')->get();
        $products = Product::all();
        return view(
            'users.otp',
            [
                'products' => $products,
                'cartItems' => $cartItems,
                'categories' =>  $categories
            ]
        );
    }

    public function sendOtp($mobile, $otp)
{
    // Semaphore API credentials
    $apiKey = 'b44a24f27a558fb5290688a7ab25aded'; // Replace with your actual Semaphore API key
    $apiUrl = 'https://api.semaphore.co/api/v4/priority'; // Fixed Semaphore API URL
    $senderName = 'MAYAHSTORE'; // Replace with your desired sender name

    // Get the session data to ensure the mobile number is correct
    $userData = session('user_data');
    if (!$userData || $userData['mobile'] !== $mobile) {
        Log::error('Session data mismatch or user not found in session', ['mobile' => $mobile]);
        throw new \Exception('Session data mismatch or user not found in session. Please try again.');
    }

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
                'sender' => $senderName,
            ]
        ]);

        $response = $client->post($apiUrl, [
            'form_params' => [
                'apikey' => $apiKey,
                'number' => $mobile,
                'message' => $message,
                'sender' => $senderName,
            ],
            'verify' => false, // Disable SSL verification (NOT RECOMMENDED FOR PRODUCTION)
        ]);
        

        // Decode the response
        $responseBody = json_decode($response->getBody(), true);

        // Log the response
        Log::info('SMS response', [
            'status_code' => $response->getStatusCode(),
            'response_body' => $responseBody,
        ]);

        // Check if the response is successful
        if ($response->getStatusCode() !== 200 || isset($responseBody['error'])) {
            Log::error('Semaphore API error', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $responseBody,
            ]);
            throw new \Exception('Failed to send OTP. Please try again.');
        }

        return $responseBody;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Log client exception details
        Log::error('Guzzle ClientException', [
            'message' => $e->getMessage(),
            'response' => $e->getResponse()->getBody()->getContents(),
        ]);
        throw new \Exception('Failed to send OTP due to a client error. Please try again.');
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // Log request exception details
        Log::error('Guzzle RequestException', [
            'message' => $e->getMessage(),
            'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
        ]);
        throw new \Exception('Failed to send OTP due to a network error. Please try again.');
    } catch (\Exception $e) {
        // Log any other exceptions
        Log::error('General Exception while sending OTP', [
            'message' => $e->getMessage(),
        ]);
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
            $otpValidityPeriod = 5; // in minutes

            // Check if OTP is still valid
            $currentDateTime = new \DateTime(); // Current time
            $interval = $currentDateTime->diff($otpCreatedAt);

            // Check if OTP is expired
            if ($interval->i > $otpValidityPeriod || ($interval->i == $otpValidityPeriod && $interval->s > 0)) {
                // OTP is expired
                return redirect()->back()->with('error', 'The OTP has expired. Please request a new one.');
            }

            // Check if OTP is correct
            if ($otp == $request->otp) {
                // Create the user
                $user = User::create([
                    'name' => $userData['name'],
                    'mobile' => $userData['mobile'],
                    'password' => $userData['password'],
                    'role_id' => $userData['role_id'],
                ]);

                // Log the user in after creation
                Auth::login($user);

                // Clear the OTP and user data from the session
                $request->session()->forget('user_data');

                // Redirect to the homepage (users.usersdashboard)
                return redirect()->route('users.usersdashboard')->with('message', 'OTP verified successfully and you are now logged in.');
            } else {
                // OTP is invalid or wrong
                return redirect()->back()->with('error', 'Invalid OTP, please try again.');
            }
        } else {
            // Handle missing session data
            return redirect()->back()->with('error', 'Session data not found. Please try again.');
        }
    }


    public function resendOtp(Request $request)
    {
        // Retrieve user data from session
        $userData = $request->session()->get('user_data');

        if (!$userData || !isset($userData['mobile'])) {
            return redirect()->back()->with('error', 'Session data not found or mobile number missing.');
        }

        // Generate a new OTP
        $otp = rand(100000, 999999); // Generate a 6-digit OTP

        // Update session data with new OTP and timestamp
        $request->session()->put('user_data', array_merge($userData, [
            'otp' => $otp,
            'otp_created_at' => now()->toDateTimeString(), // Update the OTP timestamp
        ]));

        // Send the new OTP
        try {
            $this->sendOtp($userData['mobile'], $otp);
            return redirect()->back()->with('message', 'A new OTP has been sent to your mobile number.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }
}
