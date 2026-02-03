<?php

return [
    'env' => env('IPAYMU_ENV', 'sandbox'), // sandbox|production
    'va' => env('IPAYMU_VA'),
    'api_key' => env('IPAYMU_API_KEY'),

    'return_url' => env('IPAYMU_RETURN_URL', env('APP_URL').'/ipaymu/return'),
    'cancel_url' => env('IPAYMU_CANCEL_URL', env('APP_URL').'/ipaymu/cancel'),
    'notify_url' => env('IPAYMU_NOTIFY_URL', env('APP_URL').'/ipaymu/notify'),

    'sandbox_base' => 'https://sandbox.ipaymu.com',
    'production_base' => 'https://my.ipaymu.com',
];
