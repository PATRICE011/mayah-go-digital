<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
            'mobile' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['mobile' => $formFields['mobile'], 'password' => $formFields['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleId = $user->role_id;

            switch ($roleId) {
                case 1:
                    return redirect()->to(url('/admins'))->with('message', 'Login Successful, Welcome Admin!');
                case 2:
                    return redirect()->to(url('/admins/dashboard'))->with('message', 'Login Successful, Welcome Staff!');
                case 3:
                    return redirect()->to(url('/home'))->with('message', 'Login Successful, Welcome User!');
                default:
                    Auth::logout();
                    return redirect(url('/'))->withErrors(['error' => 'Unauthorized access.']);
            }
        }

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
