<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Services\TwilioService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AccessToken')->accessToken;
            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('AccessToken')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendOTP(Request $request)
    {
        // Generate random OTP (for demonstration purposes)
        $otp = mt_rand(100000, 999999);

        // Store OTP in session or database (e.g., $request->session()->put('otp', $otp);)

        // Send OTP via SMS
        $phoneNumber = $request->input('phone_number');
        $sent = $this->twilio->sendOTP($phoneNumber, $otp);

        if ($sent) {
            return response()->json(['message' => 'OTP sent successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
    }
}
