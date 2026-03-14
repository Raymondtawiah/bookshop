<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $publicKey;
    protected $secretKey;
    protected $merchantEmail;
    protected $baseUrl;

    public function __construct()
    {
        $this->publicKey = config('paystack.publicKey');
        $this->secretKey = config('paystack.secretKey');
        $this->merchantEmail = config('paystack.merchantEmail');
        $this->baseUrl = config('paystack.paymentUrl');
        
        // Fallback to Paystack URL if config is empty
        if (empty($this->baseUrl)) {
            $this->baseUrl = 'https://api.paystack.co';
        }
    }

    /**
     * Make cURL request
     */
    private function makeRequest($method, $endpoint, $data = [])
    {
        $ch = curl_init();
        
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/json'
        ];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            Log::error('Paystack cURL error', ['error' => $error]);
            return null;
        }
        
        return json_decode($response, true);
    }

    /**
     * Initialize a payment transaction
     */
    public function initializePayment($email, $amount, $reference = null, $currency = 'GHS')
    {
        $reference = $reference ?? 'TXN-' . time() . rand(1000, 9999);
        
        $data = [
            'email' => $email,
            'amount' => $amount * 100, // Paystack expects amount in kobo/cents
            'reference' => $reference,
            'currency' => $currency,
            'callback_url' => url(config('paystack.callbackUrl')),
            'metadata' => [
                'custom_fields' => [
                    [
                        'display_name' => 'Payment Type',
                        'variable_name' => 'payment_type',
                        'value' => 'order_payment'
                    ]
                ]
            ]
        ];

        $body = $this->makeRequest('POST', '/transaction/initialize', $data);
        
        if ($body && isset($body['status']) && $body['status']) {
            return [
                'success' => true,
                'authorization_url' => $body['data']['authorization_url'],
                'reference' => $body['data']['reference'],
                'access_code' => $body['data']['access_code']
            ];
        }

        Log::error('Paystack initialization failed', ['response' => $body]);
        return [
            'success' => false,
            'message' => $body['message'] ?? 'Payment initialization failed'
        ];
    }

    /**
     * Verify a payment transaction
     */
    public function verifyPayment($reference)
    {
        $body = $this->makeRequest('GET', '/transaction/verify/' . $reference);
        
        if ($body && isset($body['status']) && $body['status'] && isset($body['data']['status']) && $body['data']['status'] === 'success') {
            return [
                'success' => true,
                'amount' => $body['data']['amount'] / 100,
                'currency' => $body['data']['currency'],
                'status' => $body['data']['status'],
                'reference' => $body['data']['reference'],
                'payment_type' => $body['data']['payment_type'] ?? 'card',
                'customer' => $body['data']['customer']['email'] ?? null
            ];
        }

        return [
            'success' => false,
            'message' => $body['message'] ?? 'Payment verification failed',
            'status' => $body['data']['status'] ?? 'failed'
        ];
    }

    /**
     * Charge a customer (for mobile money)
     */
    public function chargeMobileMoney($email, $amount, $mobileNumber, $network, $reference = null)
    {
        $reference = $reference ?? 'MOMO-' . time() . rand(1000, 9999);
        
        // Format phone number for Ghana (remove leading 0 if present)
        $formattedPhone = $mobileNumber;
        if (substr($formattedPhone, 0, 1) === '0') {
            $formattedPhone = '233' . substr($formattedPhone, 1);
        } elseif (substr($formattedPhone, 0, 3) !== '233') {
            $formattedPhone = '233' . $formattedPhone;
        }

        $data = [
            'email' => $email,
            'amount' => $amount * 100,
            'reference' => $reference,
            'currency' => 'GHS',
            'mobile' => $formattedPhone,
            'network' => $network,
            'authorization_type' => 'mobile_money',
            'callback_url' => url(config('paystack.callbackUrl')),
            'metadata' => [
                'custom_fields' => [
                    [
                        'display_name' => 'Payment Type',
                        'variable_name' => 'payment_type',
                        'value' => 'mobile_money'
                    ],
                    [
                        'display_name' => 'Mobile Number',
                        'variable_name' => 'mobile_number',
                        'value' => $mobileNumber
                    ]
                ]
            ]
        ];

        $body = $this->makeRequest('POST', '/charge', $data);
        
        Log::info('Paystack charge response', ['body' => $body, 'data' => $data]);
        
        return $body;
    }

    /**
     * Get list of banks for bank transfer
     */
    public function getBanks()
    {
        $body = $this->makeRequest('GET', '/bank');
        return $body ?? ['status' => false, 'data' => []];
    }

    /**
     * Initiate bank transfer
     */
    public function createTransferRecipient($name, $accountNumber, $bankCode)
    {
        $data = [
            'type' => 'account',
            'name' => $name,
            'account_number' => $accountNumber,
            'bank_code' => $bankCode,
            'currency' => 'GHS'
        ];

        $body = $this->makeRequest('POST', '/transferrecipient', $data);
        return $body ?? ['status' => false, 'message' => 'Failed to create transfer recipient'];
    }
}
