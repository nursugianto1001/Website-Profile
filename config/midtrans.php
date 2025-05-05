<?php

return [
    // Midtrans server key
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    // Midtrans client key
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    // Use production environment or not
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Enable 3D Secure
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    // Midtrans notification URL
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL', '/api/transactions/notification'),

    // Midtrans finish URL
    'finish_url' => env('MIDTRANS_FINISH_URL', '/booking/finish'),

    // Midtrans unfinish URL
    'unfinish_url' => env('MIDTRANS_UNFINISH_URL', '/booking/unfinish'),

    // Midtrans error URL
    'error_url' => env('MIDTRANS_ERROR_URL', '/booking/error'),
];
