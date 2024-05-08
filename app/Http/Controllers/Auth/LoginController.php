<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class LoginController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt user authentication
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('AuthToken')->plainTextToken;

                return response()->json(['user' => $user, 'token' => $token], 200);
            }

            // Attempt admin authentication
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                $admin = Auth::guard('admin')->user();
                $token = $admin->createToken('AuthToken')->plainTextToken;

                return response()->json(['admin' => $admin, 'token' => $token], 200);
            }

            // If neither user nor admin authentication succeeds, return validation error
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Login Error: ' . $e->getMessage());

            // Return a generic error response
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            // Check if the user is authenticated
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }

            // Check if the admin is authenticated
            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->user()->currentAccessToken()->delete();
            }

            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Logout Error: ' . $e->getMessage());

            // Return a generic error response
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
