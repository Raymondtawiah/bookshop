<?php

namespace App\Contracts;

interface EmailOfferTrackerInterface
{
    public function markAsOffered(int $orderId, ?string $note = null): bool;

    public function wasOffered(int $orderId): bool;

    public function getOfferedAt(int $orderId): ?\DateTimeInterface;
}
