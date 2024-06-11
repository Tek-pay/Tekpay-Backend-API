<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Method to display all users
    public function index()
    {
        // Check if the logged-in user is an admin
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $users = User::all();
        return response()->json($users);
    }

    // Method to create a new user
    public function store(Request $request)
    {
        // Authorization check
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Method to display a specific user
    public function show(User $user)
    {
        // Authorization check: User can only access their own information
        if ($user->id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($user);
    }

    // Method to update a user
    public function update(Request $request)
    {
        $user = $request->user();
        if ($request->user()->id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = $validator->validated();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json($user, 200);
    }


    // Method to delete a user
    public function destroy(User $user)
    {
        // Authorization check: User can only delete their own account
        if ($user->id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->delete();
        return response()->json(null, 204);
    }
}
