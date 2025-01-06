<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;


class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        //dd(auth()->user());
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = DatabaseNotification::find($id);
        if ($notification && $notification->notifiable_id == Auth::id()) {
            $notification->markAsRead();
            return redirect($notification->data['url']);
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $notification = DatabaseNotification::find($id);
        if ($notification && $notification->notifiable_id == Auth::id()) {
            $notification->delete();
        }
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }
}
