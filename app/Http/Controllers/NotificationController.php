<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('account_id', Auth::guard('account')->id())->latest()->paginate(10);
        return view('client.user.notifications', compact('notifications'));
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('account_id', Auth::guard('account')->id())->findOrFail($id);
        $notification->update(['read' => true]);
        return redirect()->back()->with('success', 'Thông báo đã được đánh dấu là đã đọc.');
    }
}