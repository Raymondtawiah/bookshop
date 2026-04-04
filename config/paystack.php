<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paystack Configuration
    |--------------------------------------------------------------------------
    */
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),
    'secretKey' => env('PAYSTACK_SECRET_KEY'),
    'merchantEmail' => env('PAYSTACK_MERCHANT_EMAIL', 'raymondtawiah23@gmail.com'),

    /*
    |--------------------------------------------------------------------------
    | Paystack API URL
    |--------------------------------------------------------------------------
    */
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    */
    'callbackUrl' => env('PAYSTACK_CALLBACK_URL', '/payment/callback'),

    /*
    |--------------------------------------------------------------------------
    | Bank Transfer Details
    |--------------------------------------------------------------------------
    */
    'bankDetails' => [
        'bank_name' => env('BANK_NAME', 'MTN Ghana Bank'),
        'account_name' => env('BANK_ACCOUNT_NAME', 'Bookshop GH Ltd'),
        'account_number' => env('BANK_ACCOUNT_NUMBER', '1234567890'),
        'branch' => env('BANK_BRANCH', 'Accra Main'),
    ],
];
