<?php

return [
    'defaults' => [
        'guard' => 'web',         // Default guard for user authentication
        'passwords' => 'users',   // Password reset table provider
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',  // Uses session for authentication
            'provider' => 'users',  // Points to the provider for both users and admins
        ],
    ],

    'providers' => [
        // Use a single provider for users and admins, identified by role_id
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,  // Specifies the User model
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',            // Which provider to use for both users and admins
            'table' => 'password_resets',     // Table for password resets
            'expire' => 60,                   // OTP expiration time in minutes
            'throttle' => 60,                 // Throttling attempts
        ],
    ],

    'password_timeout' => 10800,  // How long a user can stay logged in before session expires (3 hours)
];
