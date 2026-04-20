<?php

namespace App\Services;

use App\Contracts\FirebaseAuthInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseAuthService implements FirebaseAuthInterface
{
    private const CACHE_KEY = 'firebase_access_token';

    private const TOKEN_EXPIRY_BUFFER = 300;

    private const FCM_SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';

    private string $credentialsPath;

    private string $projectId;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/firebase/firebase_credentials.json');
        $this->projectId = 'football-21766';
    }

    public function getAccessToken(): string
    {
        if ($this->isTokenValid()) {
            return Cache::get(self::CACHE_KEY)['access_token'];
        }

        return $this->refreshToken();
    }

    public function isTokenValid(): bool
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return false;
        }

        $cached = Cache::get(self::CACHE_KEY);
        $expiresAt = $cached['expires_at'] ?? 0;

        return now()->timestamp < ($expiresAt - self::TOKEN_EXPIRY_BUFFER);
    }

    private function refreshToken(): string
    {
        try {
            $credentials = $this->loadCredentials();
            $jwt = $this->createJwt($credentials);

            $response = Http::asForm()
                ->timeout(30)
                ->post($credentials['token_uri'], [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('Failed to obtain access token: '.$response->body());
            }

            $token = $response->json();
            $expiresIn = $token['expires_in'] ?? 3600;
            $expiresAt = now()->addSeconds($expiresIn)->timestamp;

            Cache::put(self::CACHE_KEY, [
                'access_token' => $token['access_token'],
                'expires_at' => $expiresAt,
            ], $expiresIn);

            return $token['access_token'];
        } catch (\Exception $e) {
            Log::error('Failed to refresh Firebase access token', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function createJwt(array $credentials): string
    {
        $now = time();
        $expiry = $now + 3600;

        $header = base64_encode(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ]));

        $payload = base64_encode(json_encode([
            'iss' => $credentials['client_email'],
            'sub' => $credentials['client_email'],
            'aud' => $credentials['token_uri'],
            'iat' => $now,
            'exp' => $expiry,
            'scope' => self::FCM_SCOPE,
        ]));

        $signatureInput = $header.'.'.$payload;

        $privateKey = openssl_pkey_get_private($credentials['private_key']);
        if (! $privateKey) {
            throw new \RuntimeException('Invalid private key');
        }

        $signature = '';
        if (! openssl_sign($signatureInput, $signature, $privateKey, 'sha256')) {
            throw new \RuntimeException('Failed to sign JWT');
        }

        $signature = base64_encode($signature);

        return $signatureInput.'.'.$signature;
    }

    private function loadCredentials(): array
    {
        if (! file_exists($this->credentialsPath)) {
            throw new \RuntimeException(
                "Firebase credentials file not found at: {$this->credentialsPath}"
            );
        }

        $content = file_get_contents($this->credentialsPath);
        $credentials = json_decode($content, true);

        if (! $credentials) {
            throw new \RuntimeException('Invalid Firebase credentials JSON');
        }

        return $credentials;
    }
}
