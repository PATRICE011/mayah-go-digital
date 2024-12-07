<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function getRegister()
    {
        return view('users.register');
    }

    public function getLogin()
    {
        return view('users.login');
    }

    public function postLogin(Request $request)
    {
        $formFields = $request->validate([
            'mobile' => 'required|exists:users_area,mobile',
            'password' => 'required',
        ]);


        // Retrieve the form fields
        $formFields = $request->only('mobile', 'password');

        // Check if the mobile exists and attempt authentication
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
                    return redirect()->to(url('/admin/index'))->with('message', 'Login Successful, Welcome Admin!');
                case 2:
                    // Staff role
                    return redirect()->to(url('/admin/index'))->with('message', 'Login Successful, Welcome Staff!');
                case 3:
                    // User role
                    return redirect()->to(url('/home'))->with('message', 'Login Successful, Welcome User!');
                default:
                    // Invalid role
                    Auth::logout();
                    return redirect(url('/'))->withErrors(['error' => 'Unauthorized access']);
            }
        }

        // If authentication failed, log the failure and return error
        Log::error('Login attempt failed for mobile: ' . $formFields['mobile']);  // Corrected log usage

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
                'unique:users_area,mobile',
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $otp = rand(100000, 999999);
        $otpCreatedAt = now();

        $request->session()->put('user_data', [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_created_at' => $otpCreatedAt->toDateTimeString(),
            'role_id' => 3, // Default role
        ]);

        try {
            $otpController = new OtpController();
            $otpController->sendOtp($request->mobile, $otp);

            return redirect('user/otp')
                ->with('message', 'Registration successful! Please check your mobile for the OTP.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('name');

        return redirect('/')->with('message', 'Logout Successful');
    }
}
