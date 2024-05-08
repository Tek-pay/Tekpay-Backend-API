<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Admin login method
    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the admin
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            // Authentication successful
            $admin = Auth::guard('admin')->user();
            $token = $admin->createToken('AdminAuthToken')->plainTextToken;

            return response()->json(['admin' => $admin, 'token' => $token], 200);
        }

        // Authentication failed
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Method to display all users
    public function index()
    {
        // Check if the logged-in user is an admin
        if (!Auth::guard('admin')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Fetch all users
        $users = User::all();
        return response()->json($users);
    }

    // Method to create a new user
    public function store(Request $request)
    {
        // Check if the logged-in user is an admin
        if (!Auth::guard('admin')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Method to delete a user
    public function destroy(User $user)
    {
        // Check if the logged-in user is an admin
        if (!Auth::guard('admin')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Delete the user
        $user->delete();
        return response()->json(null, 204);
    }
}
