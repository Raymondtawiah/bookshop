<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = AdminNotification::latest()->limit(20)->get();
        $unreadCount = AdminNotification::getUnreadCount();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
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
            'unread_count' => AdminNotification::getUnreadCount()
        ]);
    }
}