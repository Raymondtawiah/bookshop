<?php

namespace App\Services;

use App\Models\AdminNotification;

class NotificationService
{
    public static function newOrder($order): void
    {
        AdminNotification::createNotification(
            'order',
            'New Order Received',
            "Order #{$order->order_number} - $".number_format($order->total_amount, 2),
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
        AdminNotification::createNotification(
            'payment',
            'Payment Received',
            "Order #{$order->order_number} has been paid - $".number_format($order->total_amount, 2),
            route('admin.orders.show', $order->id)
        );
    }
}
