<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'salla' => [
        'base_url' => env('SALLA_BASE_URL', 'https://api.salla.dev/admin/v2'),
        'client_id'          => env('SALLA_OAUTH_CLIENT_ID'),
        'client_secret'      => env('SALLA_OAUTH_CLIENT_SECRET'),
        'redirect'           => env('SALLA_OAUTH_CLIENT_REDIRECT_URI'),
        'webhook_secret'     => env('SALLA_WEBHOOK_SECRET'),
        'authorization_mode' => env('SALLA_AUTHORIZATION_MODE', 'custom')   // Supported: "easy", "custom"
    ],

    'payments' => [
        'ClickPayments' => [
            'mode' =>  env('CLICKPAY_PAYMENTS_MODE'),
            'currency' =>  env('CLICKPAY_PAYMENTS_CURRENCY'),
            'data' => [
                'live' => [
                    'url' =>  env('CLICKPAY_LIVE_ENDPOINT'),
                    'profileId' =>  env('CLICKPAY_LIVE_MERCHANT_ID'),
                    'serverKey' =>  env('CLICKPAY_LIVE_SERVER_KEY'),
                    'clientKey' =>  env('CLICKPAY_LIVE_CLIENT_KEY'),
                    'secretKey' =>  env('CLICKPAY_LIVE_SECRET_KEY'),
                    'apiKey' =>  env('CLICKPAY_LIVE_API_KEY'),
                ],
                'sandbox' => [
                    'url' =>  env('CLICKPAY_LIVE_ENDPOINT'),
                    'profileId' =>  env('CLICKPAY_SANDBOX_MERCHANT_ID'),
                    'serverKey' =>  env('CLICKPAY_SANDBOX_SERVER_KEY'),
                    'clientKey' =>  env('CLICKPAY_SANDBOX_CLIENT_KEY'),
                    'secretKey' =>  env('CLICKPAY_SANDBOX_SECRET_KEY'),
                    'apiKey' =>  env('CLICKPAY_SANDBOX_API_KEY'),
                ],
            ]
        ],
    ],

    'ollops' => [
        'environment' =>  env('OLLOPS_ENV', 'dev'),
        'base_url' =>  env('OLLOPS_BASE_URL', 'http://localhost:8000'),
        'live_api_key' =>  env('OLLOPS_API_KEY_LIVE'),
        'dev_api_key' =>  env('OLLOPS_API_KEY_DEV'),
    ],

    'aymakan' => [
        'environment' =>   env('AYMAKAN_ENV', 'dev'),
        'base_url' =>  env('AYMAKAN_BASE_URL', 'http://localhost:8000/'),
        'live_api_key' =>  env('AYMAKAN_API_KEY_LIVE'),
        'dev_api_key' =>  env('AYMAKAN_API_KEY_DEV'),
        'AYMKAN_DEBUG' =>  env('AYMKAN_DEBUG'),
        'AYMAKAN_API_URL' =>  env('AYMAKAN_API_URL'),
        'AYMAKAN_SECRET_API_KEY' =>  env('AYMAKAN_SECRET_API_KEY'),
    ],
    'wms' => [
        'base_url' =>  env('WMS_BASE_URL', 'http://localhost:8000/'),
        'Secret_Key' =>  env('WMS_SECRET_KEY', 'http://localhost:8000/'),

    ]



];
