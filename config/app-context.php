<?php

declare(strict_types = 1);

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    | Disables or enables the Laravel App Context package
    */
    'enabled' => env('LARAVEL_APP_CONTEXT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | Providers that collect context information
    | Run in the listed order
    */
    'providers' => [
        JuniorFontenele\LaravelAppContext\Providers\TimestampProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\AppProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\HostProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\RequestProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\UserProvider::class,

        // Add your custom providers here
    ],

    /*
    |--------------------------------------------------------------------------
    | Channels
    |--------------------------------------------------------------------------
    | Where the context will be registered
    */
    'channels' => [
        JuniorFontenele\LaravelAppContext\Channels\LogChannel::class,

        // Add your custom channels here
    ],
];
