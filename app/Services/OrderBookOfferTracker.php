<?php

namespace App\Services;

use App\Contracts\EmailOfferTrackerInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderBookOfferTracker implements EmailOfferTrackerInterface
{
    protected Order $order;

    public function __construct(?Order $order = null)
    {
        $this->order = $order ?? new Order;
    }

    public function markAsOffered(int $orderId, ?string $note = null): bool
    {
        $order = Order::find($orderId);

        if (! $order) {
            Log::error('OrderBookOfferTracker: Order not found', ['order_id' => $orderId]);

            return false;
        }

        $updateData = [
            'book_offered' => true,
            'book_offered_at' => now(),
        ];

        if ($note) {
            $updateData['offer_note'] = $note;
        }

        return $order->update($updateData) !== false;
    }

    public function wasOffered(int $orderId): bool
    {
        $order = Order::find($orderId);

        return $order ? (bool) $order->book_offered : false;
    }

    public function getOfferedAt(int $orderId): ?\DateTimeInterface
    {
        $order = Order::find($orderId);

        return $order ? $order->book_offered_at : null;
    }
}
