<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Stripe payment gateway.
    |
    */

    'stripe_ghs_to_usd_rate' => env('STRIPE_GHS_TO_USD_RATE', 0.08764), // 1 GHS = 0.08764 USD (1 USD = 11.41 GHS)

];