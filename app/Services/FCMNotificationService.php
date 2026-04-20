<?php

namespace App\Services;

use App\Contracts\FCMNotificationInterface;
use App\Contracts\FirebaseAuthInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMNotificationService implements FCMNotificationInterface
{
    private const FCM_ENDPOINT = 'https://fcm.googleapis.com/v1/projects/football-21766/messages:send';

    private FirebaseAuthInterface $authService;

    public function __construct(FirebaseAuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function sendNotification(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): array {
        $payload = $this->buildPayload($token, $title, $body, $data);

        return $this->sendRequest($payload);
    }

    public function sendToMultiple(
        array $tokens,
        string $title,
        string $body,
        array $data = []
    ): array {
        $results = [];

        foreach ($tokens as $token) {
            $results[] = $this->sendNotification($token, $title, $body, $data);
        }

        return $results;
    }

    private function buildPayload(
        string $token,
        string $title,
        string $body,
        array $data
    ): array {
        return [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map(fn ($value) => (string) $value, $data),
                'android' => [
                    'priority' => 'HIGH',
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function sendRequest(array $payload): array
    {
        try {
            $accessToken = $this->authService->getAccessToken();

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->timeout(30)
                ->post(self::FCM_ENDPOINT, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('messageId'),
                    'response' => $response->json(),
                ];
            }

            $error = $response->json();
            Log::error('FCM notification failed', [
                'status' => $response->status(),
                'error' => $error,
            ]);

            return [
                'success' => false,
                'error' => $error,
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('FCM notification exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
