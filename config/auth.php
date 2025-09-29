<?php

return [

    'defaults' => [
        'guard'     => env('AUTH_GUARD', 'saas'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        // 'api' => [
        //     'driver' => 'token', // or 'session' if using Sanctum with SPA
        //     'provider' => 'users',
        // ],
        'saas' => [
            'driver'   => 'session', // or 'token' if using Sanctum for API
            'provider' => 'saas_users',
        ],
    ],

    'providers' => [
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class,
        // ],
        'saas_users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Saas\User::class,
        ],
    ],

    'passwords' => [
        // 'users' => [
        //     'provider' => 'users',
        //     'table' => 'password_reset_tokens',
        //     'expire' => 60,
        //     'throttle' => 60,
        // ],
        'saas_users' => [
            'provider' => 'saas_users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
