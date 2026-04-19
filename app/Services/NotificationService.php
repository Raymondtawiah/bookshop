<?php

namespace App\Services;

use App\Models\AdminNotification;

class NotificationService
{
    public static function newOrder($order): void
    {
        $amountGhs = $order->total_amount_ghs ?? ($order->total_amount * config('settings.usd_to_ghs_rate', 12.50));
        AdminNotification::createNotification(
            'order',
            'New Order Received',
            "Order #{$order->order_number} - $".number_format($amountGhs, 2),
            route('admin.orders.show', $order->id)
        );
    }

    public static function newCoachingBooking($booking): void
    {
        AdminNotification::createNotification(
            'coaching',
            'New Coaching Booking',
            "{$booking->name} booked a coaching session",
            route('admin.coachings.index')
        );
    }

    public static function newCustomer($user): void
    {
        AdminNotification::createNotification(
            'customer',
            'New Customer Registration',
            "{$user->name} ({$user->email}) has registered",
            route('admin.customers')
        );
    }

    public static function paymentReceived($order): void
    {
        $amountGhs = $order->total_amount_ghs ?? ($order->total_amount * config('settings.usd_to_ghs_rate', 12.50));
        AdminNotification::createNotification(
            'payment',
            'Payment Received',
            "Order #{$order->order_number} has been paid - $".number_format($amountGhs, 2),
            route('admin.orders.show', $order->id)
        );
    }
}
