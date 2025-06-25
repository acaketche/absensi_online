<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'employee' => [ // ✅ Guard untuk employee (pegawai)
            'driver' => 'session',
            'provider' => 'employees',
        ],

         'student' => [
        'driver' => 'session',
        'provider' => 'students', // <== ini yang diubah
    ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'employees' => [ // ✅ Provider untuk employee
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],

        'students' => [ // <== konsisten jamak
        'driver' => 'eloquent',
        'model' => App\Models\Student::class,
    ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'employees' => [ // ✅ Reset password untuk employee
            'provider' => 'employees',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'student' => [ // ✅ Reset password untuk student
            'provider' => 'student',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
