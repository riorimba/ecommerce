<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;


class UserController extends Controller
{
    public function index(){
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    public function create(){
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

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

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user){
        $user->load('role', 'orders');
        return view('users.show', compact('user'));
    }

    public function edit(User $user){
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user){
        if ($user->role_id == 1) {
            $request->validate([
                'password' => 'required',
            ]);

            if (!Hash::check($request->password, auth()->user()->password)) {
                return redirect()->back()->withErrors(['password' => 'password confirmation failed, the user was not deleted']);
            }
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function profile(){
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request){
        $user = auth()->user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $newEmail = $validatedData['email'];
        $emailChanged = $newEmail !== $user->email;

        if ($emailChanged) {
            $user->email_verified_at = null; 
            $user->email = $newEmail;
            $user->sendEmailVerificationNotification();
        }

        $user->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}