<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json(['status' => 422, 'message' => 'Email already exists'], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), //bcrypt($request->password),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 201,
            'message' => 'User registered successfully',
            'data' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);
        
        $user = User::where('email', $request->email)->first();
        if (!$user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 401, 'message' => 'Email or password is incorrect'], 401);
        }
        if (Hash::needsRehash($user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Token expiration logic
        $remember = $request->boolean('remember');
        if ($remember) {
            // 30 days long-lived token
            $token = $user->createToken('auth_token', ['*'], now()->addDays(7))->plainTextToken;
        } else {
            // default token duration (2 hours)
            $token = $user->createToken('auth_token')->plainTextToken;
        }
        return response()->json([
            'status' => 200,
            'message' => 'User logged in successfully',
            'data' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        // Logout logic here
        $user = $request->user();
        if ($user && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json([
            'status' => 200,
            'message' => 'User logged out successfully'
        ], 200);
    }
}
