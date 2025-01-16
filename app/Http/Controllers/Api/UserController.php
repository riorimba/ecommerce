<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:250'],
            'email' => ['required', 'email', 'max:250', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);

        $user->sendEmailVerificationNotification();

        $admins = User::where('role_id', 1)->get();
        Notification::send($admins, new NewUserNotification($user->name, $user->id));

        return response()->json([
            'message' => 'You have successfully registered. Please check your email to verify your account.'
        ]);
    }

    public function login(Request $request){
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($validatedData)) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ], 401);
        }

        $user = auth()->user();
        if (!$user->hasVerifiedEmail()) {
            auth()->logout();
            return response()->json([
                'error' => 'You need to verify your email address before logging in.'
            ], 403);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'You have successfully logged in!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function me(Request $request){
        $user = $request->user();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:250'],
            'email' => ['required', 'email', 'max:250', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ]);

        $user->name = $request->name;

        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully! Please verify your new email address.',
            'user' => $user,
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out!'
        ]);
    }
}