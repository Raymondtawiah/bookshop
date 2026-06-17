<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;

class StripeService
{
    protected string $secretKey;

    protected string $publishableKey;

    protected string $currency; // Will be set to 'usd'

    protected string $webhookSecret;

    public function __construct()
    {
        $this->secretKey = config('stripe.secretKey');
        $this->publishableKey = config('stripe.publishableKey');
        // Stripe MUST use a supported currency. Force USD.
        $this->currency = 'usd';
        $this->webhookSecret = config('stripe.webhookSecret');

        Stripe::setApiKey($this->secretKey);
    }

    /**
     * Create a Stripe Checkout Session
     *
     * @param  string  $email  Customer email
     * @param  float  $amountUsd  Amount in USD (will be converted to cents)
     * @param  string  $reference  Unique order reference
     * @param  string  $successUrl  URL to redirect after successful payment
     * @param  string  $cancelUrl  URL to redirect if payment is cancelled
     * @param  array  $metadata  Additional metadata to store with the session
     * @param  string|null  $productName  Product name (defaults to 'Book Purchase')
     * @param  string|null  $productDescription  Product description (defaults to book description)
     * @return array Returns session ID and checkout URL
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
            // Convert USD to cents for Stripe (smallest currency unit)
            $amountCents = (int) round($amountUsd * 100);

            // Ensure minimum amount for Stripe (50 cents / $0.50 USD)
            if ($amountCents < 50) {
                Log::warning('Stripe amount too low, adjusting to minimum', [
                    'original_amount_usd' => $amountUsd,
                    'original_amount_cents' => $amountCents,
                    'adjusted_amount_cents' => 50,
                ]);
                $amountCents = 50;
                $amountUsd = 0.50;
            }

            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $this->currency, // 'usd'
                        'product_data' => [
                            'name' => $productName ?? 'Book Purchase',
                            'description' => $productDescription ?? 'Purchase of digital books from Bookshop',
                        ],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => $email,
                'client_reference_id' => $reference,
                'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => array_merge([
                    'currency' => $this->currency,
                ], $metadata),
            ];

            $session = CheckoutSession::create($sessionData);

            Log::info('Stripe Checkout Session created', [
                'session_id' => $session->id,
                'reference' => $reference,
                'amount_usd' => $amountUsd,
                'amount_cents' => $amountCents,
                'currency' => $this->currency,
            ]);

            return [
                'success' => true,
                'session_id' => $session->id,
                'checkout_url' => $session->url,
                'reference' => $reference,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Session creation failed', [
                'error' => $e->getMessage(),
                'amount_usd' => $amountUsd,
                'reference' => $reference,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve a Checkout Session by ID
     */
    public function retrieveSession(string $sessionId): ?CheckoutSession
    {
        try {
            return CheckoutSession::retrieve($sessionId);
        } catch (\Exception $e) {
            Log::error('Stripe session retrieval failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Verify a payment by session ID
     * Checks if the payment was successful
     *
     * @param  string  $sessionId  Stripe Checkout Session ID
     * @return array Verification result with amount, currency, status
     */
    public function verifyPayment(string $sessionId): array
    {
        try {
            $session = CheckoutSession::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                return [
                    'success' => true,
                    'amount' => $session->amount_total / 100, // Convert cents to USD (Stripe's currency)
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
            Log::error('Stripe payment verification failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the publishable key for frontend use
     */
    public function getPublishableKey(): string
    {
        return $this->publishableKey;
    }
}
