<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'mentor' => [
            'driver' => 'session',
            'provider' => 'mentors',
        ],

        'mentee' => [   // ✅ Added Mentee Guard
            'driver' => 'session',
            'provider' => 'mentees',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        'mentors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mentor::class,
        ],

        'mentees' => [   // ✅ Added Mentee Provider
            'driver' => 'eloquent',
            'model' => App\Models\Mentee::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'mentors' => [   // ✅ Added Mentor Password Reset
            'provider' => 'mentors',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'mentees' => [   // ✅ Added Mentee Password Reset
            'provider' => 'mentees',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
