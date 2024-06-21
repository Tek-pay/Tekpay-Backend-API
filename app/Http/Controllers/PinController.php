<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PinController extends Controller
{
    public function setPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $user = Auth::user();
        $user->pin = Hash::make($request->pin);
        $user->save();

        return response()->json(['message' => 'PIN set successfully']);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $user = Auth::user();

        if (Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN verified successfully']);
        } else {
            return response()->json(['error' => 'Invalid PIN'], 400);
        }
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $user = Auth::user();
        $user->pin = Hash::make($request->pin);
        $user->save();

        return response()->json(['message' => 'PIN updated successfully']);
    }
}
