<?php

return [
    'env' => env('IPAYMU_ENV', 'sandbox'), // sandbox|production
    'va' => env('IPAYMU_VA'),
    'api_key' => env('IPAYMU_API_KEY'),

    // URL callbacks sekarang generate otomatis via route() helper
    // Tidak perlu hardcode di .env lagi

    'sandbox_base' => 'https://sandbox.ipaymu.com',
    'production_base' => 'https://my.ipaymu.com',
];
