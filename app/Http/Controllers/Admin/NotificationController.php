<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * List notifikasi (dropdown / halaman)
     */
    public function index()
    {
        $notifications = Notification::where('id_pengguna', Auth::id())
            ->latest()
            ->take(20)
            ->get();

        $unreadCount = Notification::where('id_pengguna', Auth::id())
            ->unread()
            ->count();

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }

    /**
     * Ambil notifikasi (AJAX)
     */
    public function fetch()
    {
        $notifications = Notification::where('id_pengguna', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Tandai satu notifikasi sebagai dibaca
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id_notification', $id)
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();

        if (! $notification->is_read) {
            $notification->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect' => $notification->url
        ]);
    }

    /**
     * Tandai semua sebagai dibaca
     */
    public function markAllAsRead()
    {
        Notification::where('id_pengguna', Auth::id())
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }
}
