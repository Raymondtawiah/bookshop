<?php

namespace App\Contracts;

interface FCMNotificationInterface
{
    public function sendNotification(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): array;

    public function sendToMultiple(
        array $tokens,
        string $title,
        string $body,
        array $data = []
    ): array;
}
