<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

            $chatNotifications = \App\Models\ChatMessage::where('status', 'unread')
                ->whereIn('sender_type', ['customer', 'guest'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($chat) {
                    return [
                        'id' => 'chat_'.$chat->id,
                        'type' => 'chat',
                        'title' => 'New Chat Message',
                        'message' => $chat->message,
                        'created_at' => $chat->created_at->toIso8601String(),
                        'is_read' => false,
                        'link' => route('admin.chat.index'),
                    ];
                });

            $allNotifications = $notifications->concat($chatNotifications)->sortByDesc('created_at')->values();

            $unreadCount = AdminNotification::getUnreadCount();
            $chatUnreadCount = \App\Models\ChatMessage::where('status', 'unread')
                ->whereIn('sender_type', ['customer', 'guest'])
                ->count();

            return response()->json([
                'notifications' => $allNotifications,
                'unread_count' => $unreadCount + $chatUnreadCount,
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
        $unreadCount = AdminNotification::getUnreadCount();
        $chatUnreadCount = \App\Models\ChatMessage::where('status', 'unread')
            ->whereIn('sender_type', ['customer', 'guest'])
            ->count();

        return response()->json([
            'unread_count' => $unreadCount + $chatUnreadCount,
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

    public function broadcastForm()
    {
        return view('admin.broadcast');
    }

    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'book_update' => 'nullable|string|max:2000',
            'webinar_update' => 'nullable|string|max:2000',
        ]);

        $customers = User::where('is_admin', false)
            ->where('is_staff', false)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        if ($customers->isEmpty()) {
            return back()->with('error', 'No customers found to send notifications to.');
        }

        $subject = $request->input('subject');
        $message = $request->input('message');
        $bookUpdate = $request->input('book_update');
        $webinarUpdate = $request->input('webinar_update');
        $sentCount = 0;
        $failedCount = 0;

        foreach ($customers as $customer) {
            try {
                Mail::send('emails.broadcast', [
                    'name' => $customer->name,
                    'subject' => $subject,
                    'message' => $message,
                    'bookUpdate' => $bookUpdate,
                    'webinarUpdate' => $webinarUpdate,
                    'url' => url('/'),
                ], function ($mail) use ($customer, $subject) {
                    $mail->to($customer->email, $customer->name)
                        ->subject($subject);
                });

                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Broadcast email failed', [
                    'email' => $customer->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        AdminNotification::createNotification(
            'customer',
            'Broadcast Sent',
            "Email broadcast sent to {$sentCount} customers. Failed: {$failedCount}.",
            route('admin.notifications')
        );

        return back()->with('success', "Broadcast sent to {$sentCount} customers. Failed: {$failedCount}.");
    }
}
