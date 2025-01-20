<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;


class AuthenticationController extends Controller
{
    public function register(){
        return view('auth.register');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|confirmed',
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

        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', 'User created successfully. Please check your email to verify your account.');
    }

    public function login(){
        return view('auth.login');
    }

    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
            ])->onlyInput('email');
        }

        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            //Auth::logout();
            return redirect()->route('verification.notice')->with('message', 'Please verify your email address.');
        }

        $request->session()->regenerate();

        return to_route('dashboard')
            ->withSuccess('You have successfully logged in!');
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')->withSuccess('You have logged out successfully!');;
    }
}
