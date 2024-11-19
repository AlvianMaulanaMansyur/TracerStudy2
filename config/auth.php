<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'alumni'), // Ubah ini jika ingin menggunakan broker lain
    ],

    'guards' => [
        'alumni' => [
            'driver' => 'session',
            'provider' => 'alumni',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],
    
    'providers' => [
        'alumni' => [
            'driver' => 'eloquent',
            'model' => App\Models\Alumni::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    */

    'passwords' => [
        'alumni' => [ // Pastikan ini sesuai dengan provider yang kamu gunakan
            'provider' => 'alumni',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_resets'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'admin' => [
            'provider' => 'admin',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_resets'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
