<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth\SignIn\FailedToSignIn;
use Kreait\Firebase\Auth\SignIn\FailedToSignInWithEmail;
use Kreait\Firebase\Auth\SignIn\FailedToSignInWithPhone;
use Kreait\Firebase\Auth\SignIn\WithPhoneAndPassword;
use Kreait\Firebase\Exception\Auth\PhoneNumberAlreadyExists;
use Kreait\Firebase\Exception\Auth\PhoneNumberNotFound;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\FirebaseException;

class OTPController extends Controller
{
    public function generateOTP(Request $request)
    {
        $phoneNumber = $request->input('phone_number');

        try {
            $firebase = app('firebase');
            $auth = $firebase->getAuth();

            $verification = $auth->startPhoneVerification($phoneNumber);

            // Store verification ID in session
            session()->put('verification_id', $verification->verificationId());

            return response()->json(['message' => 'OTP sent successfully']);
        } catch (FirebaseException $e) {
            return response()->json(['error' => 'Failed to send OTP: ' . $e->getMessage()], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $otp = $request->input('otp');
        $verificationId = session()->get('verification_id');

        try {
            $firebase = app('firebase');
            $auth = $firebase->getAuth();

            $auth->verifyPhoneSignIn($verificationId, $otp);

            // Clear verification ID from session
            session()->forget('verification_id');

            return response()->json(['message' => 'OTP verified successfully']);
        } catch (FirebaseException $e) {
            return response()->json(['error' => 'Failed to verify OTP: ' . $e->getMessage()], 400);
        }
    }
}
