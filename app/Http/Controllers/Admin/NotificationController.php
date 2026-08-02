<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminNotification::query()
            ->where(function ($q) {
                $q->where('is_read', false)
                  ->orWhere('created_at', '>=', now()->subDays(30));
            });

        if ($request->wantsJson() || $request->ajax()) {
            $notifications = $query->latest()->limit(20)->get();
            $unreadCount = AdminNotification::getUnreadCount();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        }

        $notifications = $query->latest()->limit(50)->get();
        $unreadCount = AdminNotification::getUnreadCount();

        return view('admin.notifications', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Request $request): JsonResponse
    {
        $notification = AdminNotification::find($request->id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        AdminNotification::markAllAsRead();

        return response()->json(['success' => true]);
    }

    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'unread_count' => AdminNotification::getUnreadCount(),
        ]);
    }

    public function toggleRead(Request $request): JsonResponse
    {
        $notification = AdminNotification::find($request->id);
        if ($notification) {
            $notification->update(['is_read' => ! $notification->is_read]);
        }

        return response()->json(['success' => true, 'is_read' => $notification->is_read ?? false]);
    }

    public function delete(Request $request): JsonResponse
    {
        $notification = AdminNotification::find($request->id);
        if ($notification) {
            $notification->delete();
        }

        return response()->json(['success' => true]);
    }

    public function cleanup(Request $request): JsonResponse
    {
        $query = AdminNotification::where('is_read', true)
            ->where('created_at', '<', now()->subDays(30));

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        $deleted = $query->delete();

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
        ]);
    }
}
