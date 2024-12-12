<?php

return [
    'base_url' => env('SALLA_BASE_URL', 'https://api.salla.dev/admin/v2'),
    'client_id' => env('SALLA_OAUTH_CLIENT_ID'),
    'client_secret' => env('SALLA_OAUTH_CLIENT_SECRET'),
    'redirect_uri' => env('SALLA_OAUTH_CLIENT_REDIRECT_URI'),
    'scope' => 'offline_access',  // Needed for refresh tokens
];

