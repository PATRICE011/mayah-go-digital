<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function showOtp()
    {

        return view('users.otp');
    }

    public function sendOtp($mobile, $otp)
    {
        $apiKey = 'b44a24f27a558fb5290688a7ab25aded';
        $apiUrl = 'https://api.semaphore.co/api/v4/priority';
        $senderName = 'MAYAHSTORE';

        $userData = session('user_data');
        if (!$userData || $userData['mobile'] !== $mobile) {
            Log::error('Session data mismatch or user not found in session', ['mobile' => $mobile]);
            throw new \Exception('Session data mismatch or user not found in session. Please try again.');
        }

        $client = new Client();
        $message = "Your OTP for MAYAHSTORE is $otp. Please enter this code to verify your account. This code is valid for 1 minute.";

        try {
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
                'verify' => false,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('SMS response', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $responseBody,
            ]);

            if ($response->getStatusCode() !== 200 || isset($responseBody['error'])) {
                Log::error('Semaphore API error', [
                    'status_code' => $response->getStatusCode(),
                    'response_body' => $responseBody,
                ]);
                throw new \Exception('Failed to send OTP. Please try again.');
            }

            return $responseBody;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Guzzle ClientException', [
                'message' => $e->getMessage(),
                'response' => $e->getResponse()->getBody()->getContents(),
            ]);
            throw new \Exception('Failed to send OTP due to a client error. Please try again.');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Guzzle RequestException', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            throw new \Exception('Failed to send OTP due to a network error. Please try again.');
        } catch (\Exception $e) {
            Log::error('General Exception while sending OTP', [
                'message' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to send OTP due to a system error. Please try again.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string']);

        // Get user data from session
        $userData = $request->session()->get('user_data');
        Log::debug('User data in session', $userData); // Log session data

        if ($userData) {
            $otp = $userData['otp'];
            $otpCreatedAt = new \DateTime($userData['otp_created_at']);
            $otpValidityPeriod = 5;

            $currentDateTime = new \DateTime();
            $interval = $currentDateTime->diff($otpCreatedAt);

            // Check OTP expiry
            Log::debug('OTP validity check', [
                'otp_created_at' => $otpCreatedAt->format('Y-m-d H:i:s'),
                'current_time' => $currentDateTime->format('Y-m-d H:i:s'),
                'interval' => $interval->format('%i minutes %s seconds')
            ]);

            if ($interval->i > $otpValidityPeriod || ($interval->i == $otpValidityPeriod && $interval->s > 0)) {
                return redirect()->back()->with('error', 'The OTP has expired. Please request a new one.');
            }

            // Check if OTP matches
            if ($otp == $request->otp) {
                // Insert new user into the database with hashed password
                // $hashedPassword = bcrypt($userData['password']);
                // Log::debug('Password before hashing', ['password' => $userData['password']]);
                // Log::debug('Password after hashing', ['hashedPassword' => $hashedPassword]);

                DB::table('users_area')->insert([
                    'name' => $userData['name'],
                    'mobile' => $userData['mobile'],
                    'password' => $userData['password'],
                    'role_id' => $userData['role_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Retrieve user and log in
                $user = DB::table('users_area')->where('mobile', $userData['mobile'])->first();
                Log::debug('User inserted into DB', (array) $user); // Log inserted user

                if ($user) {
                    Auth::loginUsingId($user->id); // Log the user in
                    $request->session()->forget('user_data'); // Clear session data
                    return redirect('/home')->with('message', 'OTP verified successfully and you are now logged in.');
                } else {
                    return redirect()->back()->with('error', 'User creation failed. Please try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid OTP, please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'Session data not found. Please try again.');
        }
    }

    public function resendOtp(Request $request)
    {
        $userData = $request->session()->get('user_data');

        if (!$userData || !isset($userData['mobile'])) {
            return redirect()->back()->with('error', 'Session data not found or mobile number missing.');
        }

        $otp = rand(100000, 999999);

        $request->session()->put('user_data', array_merge($userData, [
            'otp' => $otp,
            'otp_created_at' => now()->toDateTimeString(),
        ]));

        try {
            $this->sendOtp($userData['mobile'], $otp);
            return redirect()->back()->with('message', 'A new OTP has been sent to your mobile number.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }
}
