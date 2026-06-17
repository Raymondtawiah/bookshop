<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\User;

class PushNotificationService
{
    private FCMNotificationService $fcmService;

    public function __construct(FCMNotificationService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendToUser(User $user, string $title, string $body, array $data = []): array
    {
        $tokens = $user->deviceTokens()->pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No device tokens found for user',
            ];
        }

        return $this->fcmService->sendToMultiple($tokens, $title, $body, $data);
    }

    public function sendToAllUsers(string $title, string $body, array $data = []): array
    {
        $tokens = DeviceToken::pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No device tokens found',
            ];
        }

        return $this->fcmService->sendToMultiple($tokens, $title, $body, $data);
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        return $this->fcmService->sendNotification($token, $title, $body, $data);
    }

    public function storeDeviceToken(int $userId, string $fcmToken, string $deviceType = 'web'): DeviceToken
    {
        return DeviceToken::updateOrCreate(
            [
                'user_id' => $userId,
                'fcm_token' => $fcmToken,
            ],
            [
                'device_type' => $deviceType,
            ]
        );
    }

    public function removeDeviceToken(int $userId, string $fcmToken): bool
    {
        return DeviceToken::where('user_id', $userId)
            ->where('fcm_token', $fcmToken)
            ->delete() > 0;
    }
}
