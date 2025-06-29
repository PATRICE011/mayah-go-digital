<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class UpdateProfileController extends Controller
{
    // Send code to user's phone number
    public function sendCode(Request $request)
{
    // Get the authenticated user
    $userId = Auth::id();
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated.'], 401);
    }

    // Fetch the last OTP request time from the users_area table
    $userArea = DB::table('users_area')->where('id', $userId)->first();

    // Check for last OTP request time
    if ($userArea && $userArea->otp_created_at) {
        $lastOtpRequestTime = Carbon::parse($userArea->otp_created_at);

        // Check if 1 minute restriction applies
        if ($lastOtpRequestTime->addMinute() > Carbon::now()) {
            $remainingTime = Carbon::now()->diffInSeconds($lastOtpRequestTime->addMinute());

            return response()->json([
                'error' => 'You can request OTP only after 1 minute.',
                'remaining_time' => $remainingTime,
            ], 400);
        }
    }

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Update the OTP and related fields in the database
    DB::table('users_area')->where('id', $userId)->update([
        'otp' => $otp,
        'otp_created_at' => Carbon::now(),
        'otp_attempts' => 0,
    ]);

    // Send OTP using Semaphore
    $response = Http::withOptions(['verify' => false])
        ->post('https://api.semaphore.co/api/v4/priority', [
            'apikey' => env('SEMAPHORE_API_KEY'),
            'number' => $user->mobile,
            'message' => "Your OTP is $otp. This code is valid for 5 minutes.",
            'sendername' => env('SEMAPHORE_SENDER_NAME'),
        ]);

    // Return response based on Semaphore API result
    if ($response->successful()) {
        return response()->json(['message' => 'OTP sent successfully!']);
    } else {
        return response()->json(['error' => 'Failed to send OTP. Please try again later.'], 500);
    }
}


public function updateProfile(Request $request)
{
    // Validate input fields
    $request->validate([
        'name' => 'required|string|max:255',
        'mobile' => 'required|string|unique:users_area,mobile,' . Auth::id(),
        'otp' => 'required|numeric|digits:6',
    ]);

    // Get the authenticated user
    $userId = Auth::id();

    // Fetch the user using query builder (bypassing Eloquent save issues)
    $user = DB::table('users_area')->where('id', $userId)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'User not found.')->with('active_tab', 'update-profile');
    }

    // Validate OTP (expiration time is now 5 minutes)
    $otpExpired = Carbon::parse($user->otp_created_at)->diffInMinutes(Carbon::now()) > 5;
    if ($user->otp !== $request->otp || $otpExpired) {
        return redirect()->back()->with('error', 'Invalid or expired OTP.')->with('active_tab', 'update-profile');
    }

    // Update user profile directly in the database
    DB::table('users_area')->where('id', $userId)->update([
        'name' => $request->name,
        'mobile' => $request->mobile,
        'otp' => null, // Clear OTP after successful update
    ]);

    return redirect()->back()->with('message', 'Profile updated successfully!')->with('active_tab', 'update-profile');
}

public function changePassword(Request $request)
{
    // Validate the input
    $request->validate([
        'old_password' => 'required|string',
        'new_password' => [
            'required',
            'string',
            'min:5',
            'regex:/^(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{5,}$/',
            'confirmed',
        ],
        'otp' => 'required|numeric|digits:6',
    ]);
    

    // Get the authenticated user
    $userId = Auth::id();
    $user = DB::table('users_area')->where('id', $userId)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'User not found.')->with('active_tab', 'change-password');
    }

    // Verify OTP (expiration time is now 5 minutes)
    $otpExpired = Carbon::parse($user->otp_created_at)->diffInMinutes(Carbon::now()) > 5;
    if ($user->otp !== $request->otp || $otpExpired) {
        return redirect()->back()->with('error', 'Invalid or expired OTP.')->with('active_tab', 'change-password');
    }

    // Verify old password
    // if (!Hash::check($request->old_password, $user->password)) {
    //     return redirect()->back()->with('error', 'Old password is incorrect.')->with('active_tab', 'change-password');
    // }

    // Update the password
    DB::table('users_area')->where('id', $userId)->update([
        'password' => Hash::make($request->new_password),
        'otp' => null, // Clear OTP after successful update
    ]);

    return redirect()->back()->with('message', 'Password changed successfully!')->with('active_tab', 'change-password');
}

public function validateOldPassword(Request $request)
{
    $request->validate([
        'old_password' => 'required|string',
    ]);

    $userId = Auth::id();
    $user = DB::table('users_area')->where('id', $userId)->first();

    if (!$user || !Hash::check($request->old_password, $user->password)) {
        return response()->json(['valid' => false]);
    }

    return response()->json(['valid' => true]);
}

}
