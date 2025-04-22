<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;

class AdminSettings extends Controller
{
    public function index()
    {
        return view('admins.settings');
    }

    public function sendOtp(Request $request)
    {
        // Validate the mobile number
        $request->validate([
            'mobile' => 'required|string|regex:/^\+?[0-9]{10,15}$/', // Validate mobile number format
        ]);

        $mobile = $request->input('mobile');

        // Check if the mobile number matches the authenticated user's mobile
        $user = Auth::user();

        if ($user->mobile !== $mobile) {
            return response()->json(['error' => 'Mobile number does not match your account.'], 400);
        }

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Update the OTP and timestamp in the user's record
        $user->otp = $otp;
        $user->otp_created_at = now();
        $user->save();

        // Send OTP via SMS
        try {
            $this->sendOtpToMobile($mobile, $otp);
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your mobile number.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    // Helper method to send OTP via SMS
    private function sendOtpToMobile($mobile, $otp)
    {
        $apiKey = 'b44a24f27a558fb5290688a7ab25aded'; // Your API key for SMS provider
        $apiUrl = 'https://api.semaphore.co/api/v4/priority'; // Example API endpoint
        $senderName = 'MAYAHSTORE'; // Sender name

        $message = "Your OTP for MAYAHSTORE is $otp. Please enter this code to verify your account. This code is valid for 5 minutes.";

        // // For development purposes, we'll skip the actual API call
        // // In production, uncomment the following code

        $client = new Client();
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
            throw new \Exception('Failed to send OTP. Please try again.');
        }

        return $responseBody;


        // // Returning a mock success response for development
        // return ['success' => true];
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'otp' => 'required|numeric',
            'password' => 'nullable|string|min:8|regex:/[0-9]/|regex:/[^A-Za-z0-9]/',
            'password_confirmation' => 'nullable|same:password',
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
            'password.regex' => 'Password must contain at least one number and one special character.',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if OTP matches and is still valid
        $otp = $user->otp;

        if (!$otp) {
            return redirect()->back()->with('error', 'Please request an OTP first.');
        }

        $otpCreatedAt = Carbon::parse($user->otp_created_at);
        $otpValidityPeriod = 5; // OTP validity period in minutes

        // Check if the OTP has expired
        if (Carbon::now()->diffInMinutes($otpCreatedAt) > $otpValidityPeriod) {
            return redirect()->back()->with('error', 'The OTP has expired. Please request a new one.');
        }

        // Validate OTP entered by the user
        if ($otp != $request->otp) {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }

        // Proceed to update user profile
        $user->name = $request->name;
        $user->mobile = $request->phone;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Clear OTP after successful verification
        $user->otp = null;
        $user->otp_created_at = null;
        $user->save();

        return redirect()->route('admins.settings')->with('success', 'Profile updated successfully.');
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        // Check if OTP was recently sent
        if ($user->otp_created_at) {
            $lastOtpTime = Carbon::parse($user->otp_created_at);
            $secondsRemaining = 60 - Carbon::now()->diffInSeconds($lastOtpTime);

            if ($secondsRemaining > 0) {
                return response()->json([
                    'success' => false,
                    'error' => "Please wait {$secondsRemaining} seconds before resending OTP.",
                    'seconds_remaining' => $secondsRemaining
                ], 400);
            }
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your mobile number.'
            ], 200);
        }

        // Create a new request with mobile number
        $newRequest = new Request();
        $newRequest->merge(['mobile' => $user->mobile]);

        return $this->sendOtp($newRequest);
    }
}
