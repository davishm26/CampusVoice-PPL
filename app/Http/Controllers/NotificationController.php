<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected function getActiveUser(): User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if (request()->filled('user_id')) {
            $u = User::find(request('user_id'));
            if ($u) return $u;
        }

        if (request()->filled('nim')) {
            $u = User::where('nim', request('nim'))->first();
            if ($u) return $u;
        }

        return User::first() ?? new User();
    }

    public function index(Request $request)
    {
        $authUser = $this->getActiveUser();
        $notifications = AppNotification::where('user_id', $authUser->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();

        if ($request->routeIs('admin.notifikasi') || $request->is('admin/*') || $authUser->role === 'Admin SSC') {
            return view('admin.notifikasi', compact('notifications', 'authUser', 'unreadCount'));
        }

        if ($request->routeIs('unit.notifikasi') || $request->is('unit/*') || $authUser->role === 'Petugas Unit') {
            return view('unit.notifikasi', compact('notifications', 'authUser', 'unreadCount'));
        }

        return view('notifikasi', compact('notifications', 'authUser', 'unreadCount'));
    }

    public function markAllAsRead()
    {
        $authUser = $this->getActiveUser();
        AppNotification::where('user_id', $authUser->id)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function markAsRead($id)
    {
        $authUser = $this->getActiveUser();
        $notif = AppNotification::where('user_id', $authUser->id)->where('id', $id)->first();

        if ($notif) {
            $notif->update(['is_read' => true]);
            if ($notif->tiket) {
                return redirect()->route('laporan.detail', $notif->tiket);
            }
        }

        return redirect()->back();
    }
}

