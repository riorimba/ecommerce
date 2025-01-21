<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Notification;

class AuthenticationController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:250'],
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $validatedData['role_id'] = 2;

        $user = User::withTrashed()->updateOrCreate(
            ['email' => $validatedData['email']],
            $validatedData
        );

        if ($user->trashed()) {
            $user->restore();
            $user->email_verified_at = null;
            $user->save();
        }

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

        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            //Auth::logout();
            return response()->json([
                'message' => 'Please verify your email address.'
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out!'
        ]);
    }

    public function requestVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $code = Crypt::encryptString(json_encode([
            'email' => $user->email,
            'timestamp' => now()->timestamp,
        ]));

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return response()->json(['message' => 'Verification code sent to your email.'], 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $data = json_decode(Crypt::decryptString($request->code), true);

            if (now()->timestamp - $data['timestamp'] > 3600) { // Token valid selama 1 jam
                return response()->json(['message' => 'Verification code expired.'], 400);
            }

            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                return response()->json(['message' => 'Invalid verification code.'], 400);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }
    }
}