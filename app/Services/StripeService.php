<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;

class StripeService
{
    protected ?string $secretKey;
    protected string $publishableKey;
    protected string $currency;
    protected ?string $webhookSecret;

    public function __construct()
    {
        // Safe fallback values to prevent Laravel crash
        $this->secretKey = config('stripe.secretKey') ?? '';
        $this->publishableKey = config('stripe.publishableKey') ?? '';
        $this->webhookSecret = config('stripe.webhookSecret') ?? '';

        // Stripe MUST use a supported currency
        $this->currency = 'usd';

        // Only set API key if it exists (prevents crash)
        if (!empty($this->secretKey)) {
            Stripe::setApiKey($this->secretKey);
        } else {
            Log::warning('Stripe secret key is missing in config.');
        }
    }

    /**
     * Create a Stripe Checkout Session
     */
    public function createCheckoutSession(
        string $email,
        float $amountUsd,
        string $reference,
        string $successUrl,
        string $cancelUrl,
        array $metadata = [],
        ?string $productName = null,
        ?string $productDescription = null
    ): array {
        try {
            if (empty($this->secretKey)) {
                return [
                    'success' => false,
                    'message' => 'Stripe secret key is missing',
                ];
            }

            $amountCents = (int) round($amountUsd * 100);

            if ($amountCents < 50) {
                $amountCents = 50;
                $amountUsd = 0.50;
            }

            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $this->currency,
                        'product_data' => [
                            'name' => $productName ?? 'Book Purchase',
                            'description' => $productDescription ?? 'Purchase from Bookshop',
                        ],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => $email,
                'client_reference_id' => $reference,
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => array_merge([
                    'currency' => $this->currency,
                ], $metadata),
            ];

            $session = CheckoutSession::create($sessionData);

            return [
                'success' => true,
                'session_id' => $session->id,
                'checkout_url' => $session->url,
                'reference' => $reference,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe session creation failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve session
     */
    public function retrieveSession(string $sessionId): ?CheckoutSession
    {
        try {
            return CheckoutSession::retrieve($sessionId);
        } catch (\Exception $e) {
            Log::error('Stripe session retrieval failed', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment(string $sessionId): array
    {
        try {
            $session = CheckoutSession::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                return [
                    'success' => true,
                    'amount' => $session->amount_total / 100,
                    'currency' => $session->currency,
                    'status' => $session->payment_status,
                    'session_id' => $session->id,
                    'customer_email' => $session->customer_details->email ?? null,
                    'reference' => $session->client_reference_id,
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment not completed',
                'status' => $session->payment_status,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe verification failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get publishable key
     */
    public function getPublishableKey(): string
    {
        return $this->publishableKey ?? '';
    }
}