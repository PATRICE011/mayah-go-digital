<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\OtpController;

class AuthController extends Controller
{
    // Display the registration form
    public function getRegister()
    {
        return view('users.register');
    }

    // Display the login form
    public function getLogin()
    {
        return view('users.login');
    }

    // Handle login request
    public function postLogin(Request $request)
    {
        // Validate login form fields
        $formFields = $request->validate([
            'mobile' => 'required|exists:users_area,mobile',
            'password' => 'required',
        ]);

        // Attempt login using mobile and password
        if (Auth::attempt(['mobile' => $formFields['mobile'], 'password' => $formFields['password']])) {
            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // Handle role-based redirection
            $roleId = $user->role_id;
            switch ($roleId) {
                case 1:
                    // Admin role
                    return redirect('/admin')->with('message', 'Login Successful, Welcome Admin!');
                case 2:
                    // Staff role
                    return redirect('/admin')->with('message', 'Login Successful, Welcome Staff!');
                case 3:
                    // User role
                    return redirect('/user')->with('message', 'Login Successful, Welcome User!');
                default:
                    // Invalid role
                    Auth::logout();
                    return redirect(url('/'))->withErrors(['error' => 'Unauthorized access']);
            }
        }

        // Log failed login attempt
        Log::error('Login attempt failed for mobile: ' . $formFields['mobile']);

        // Return error if authentication failed
        return back()->withErrors([
            'mobile' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle registration request
    public function postRegister(Request $request)
    {
        // Validate registration form fields
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'string',
                'max:15',
                'unique:users_area,mobile',
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $otpCreatedAt = now();

        // Store user data and OTP in session
        $request->session()->put('user_data', [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_created_at' => $otpCreatedAt->toDateTimeString(),
            'role_id' => 3, // Default role
        ]);

        try {
            // Send OTP to the user's mobile
            $otpController = new OtpController();
            $otpController->sendOtp($request->mobile, $otp);

            // Redirect to OTP verification page
            return redirect('user/otp')
                ->with('message', 'Registration successful! Please check your mobile for the OTP.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    // Handle logout request
    public function logout(Request $request)
    {
        // Log out the current user
        Auth::logout();

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear session data and redirect to home page
        return redirect('/')->with('message', 'Logout Successful');
    }
}
