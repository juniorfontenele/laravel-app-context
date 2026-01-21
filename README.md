# Laravel App Context

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juniorfontenele/laravel-app-context.svg?style=flat-square)](https://packagist.org/packages/juniorfontenele/laravel-app-context)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/juniorfontenele/laravel-app-context/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/juniorfontenele/laravel-app-context/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/juniorfontenele/laravel-app-context/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/juniorfontenele/laravel-app-context/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/juniorfontenele/laravel-app-context.svg?style=flat-square)](https://packagist.org/packages/juniorfontenele/laravel-app-context)

A powerful and extensible package for managing application context in Laravel. Automatically collect and distribute context information from multiple sources (user, request, environment, etc.) to various channels (logs, monitoring systems, etc.).

## Features

- **Automatic Context Collection**: Built-in providers for timestamp, app info, host info, request data, and user information
- **Extensible Architecture**: Easy to create custom providers and channels
- **Conditional Execution**: Providers can determine when they should run
- **Multiple Channels**: Register context in different systems (logs, monitoring, etc.)
- **Facade Support**: Clean and elegant API using Laravel facades
- **Configuration-Based**: Manage providers and channels through a simple config file

## Installation

You can install the package via composer:

```bash
composer require juniorfontenele/laravel-app-context
```

The package will automatically register its service provider.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="laravel-app-context-config"
```

This will create a `config/app-context.php` file with the following structure:

```php
return [
    'enabled' => env('LARAVEL_APP_CONTEXT_ENABLED', true),
    
    'providers' => [
        // Built-in providers
        JuniorFontenele\LaravelAppContext\Providers\TimestampProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\AppProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\HostProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\RequestProvider::class,
        JuniorFontenele\LaravelAppContext\Providers\UserProvider::class,
    ],
    
    'channels' => [
        JuniorFontenele\LaravelAppContext\Channels\LogChannel::class,
        
        // Add your custom channels here
    ],
];
```

## Basic Usage

### Using the Facade

```php
use JuniorFontenele\LaravelAppContext\Facades\AppContext;

// Get all context
$context = AppContext::all();

// Get a specific context value
$userId = AppContext::get('user.id');
$appName = AppContext::get('app.name');

// Get with a default value
$userName = AppContext::get('user.name', 'Guest');

// Set a custom value
AppContext::set('custom.key', 'custom value');

// Clear the context
AppContext::clear();
```

### Context Structure

The default context includes:

```php
[
    'timestamp' => '2024-01-21T10:30:00+00:00',
    'app' => [
        'name' => 'Laravel',
        'env' => 'production',
        'debug' => false,
        'url' => 'https://example.com',
        'timezone' => 'UTC',
        'locale' => 'en',
        'origin' => 'web', // or 'console'
    ],
    'host' => [
        'name' => 'server-01',
        'ip' => '192.168.1.100',
    ],
    'request' => [ // Only available in web requests
        'ip' => '192.168.1.1',
        'method' => 'GET',
        'url' => 'https://example.com/api/users',
        'host' => 'example.com',
        'scheme' => 'https',
        'locale' => 'en',
        'referer' => 'https://example.com',
        'user_agent' => 'Mozilla/5.0...',
        'accept_language' => 'en-US,en;q=0.9',
    ],
    'user' => [ // Only when authenticated
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ],
]
```

## Creating Custom Providers

Providers are classes that collect specific context information. Create a custom provider by implementing the `ContextProvider` interface or extending `AbstractProvider`:

### Basic Provider

```php
<?php

namespace App\Context\Providers;

use JuniorFontenele\LaravelAppContext\Providers\AbstractProvider;

class CustomProvider extends AbstractProvider
{
    public function getContext(): array
    {
        return [
            'custom' => [
                'key' => 'value',
                'data' => $this->getCustomData(),
            ],
        ];
    }
    
    private function getCustomData(): array
    {
        return [
            'foo' => 'bar',
        ];
    }
}
```

### Conditional Provider

Control when your provider should run using the `shouldRun()` method:

```php
<?php

namespace App\Context\Providers;

use JuniorFontenele\LaravelAppContext\Providers\AbstractProvider;

class DatabaseProvider extends AbstractProvider
{
    public function shouldRun(): bool
    {
        // Only run if database is connected
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getContext(): array
    {
        return [
            'database' => [
                'connection' => config('database.default'),
                'name' => config('database.connections.' . config('database.default') . '.database'),
            ],
        ];
    }
}
```

### Registering Custom Providers

Add your custom provider to the `config/app-context.php` file:

```php
'providers' => [
    // Built-in providers
    JuniorFontenele\LaravelAppContext\Providers\TimestampProvider::class,
    JuniorFontenele\LaravelAppContext\Providers\AppProvider::class,
    
    // Your custom providers
    App\Context\Providers\CustomProvider::class,
    App\Context\Providers\DatabaseProvider::class,
],
```

Or register programmatically in a service provider:

```php
use JuniorFontenele\LaravelAppContext\Facades\AppContext;
use App\Context\Providers\CustomProvider;

public function boot()
{
    AppContext::addProvider(new CustomProvider());
}
```

## Creating Custom Channels

Channels receive the resolved context and register it in different systems (logs, error tracking, etc.). They don't dispatch the context immediately, but rather add it to systems that will use it later. Create a custom channel by implementing the `ContextChannel` interface:

### Basic Channel

```php
<?php

namespace App\Context\Channels;

use JuniorFontenele\LaravelAppContext\Contracts\ContextChannel;
use Illuminate\Support\Facades\Cache;

class CacheChannel implements ContextChannel
{
    public function registerContext(array $context): void
    {
        // Register context in cache for later use
        Cache::put('app.context', $context, now()->addMinutes(5));
    }
}
```

### Advanced Channel Example

```php
<?php

namespace App\Context\Channels;

use JuniorFontenele\LaravelAppContext\Contracts\ContextChannel;
use Sentry\State\Scope;

class SentryChannel implements ContextChannel
{
    public function registerContext(array $context): void
    {
        // Register context in Sentry for error tracking
        // This context will be included in all Sentry error reports
        \Sentry\configureScope(function (Scope $scope) use ($context) {
            $scope->setContext('app', $context['app'] ?? []);
            $scope->setContext('host', $context['host'] ?? []);
            $scope->setContext('request', $context['request'] ?? []);
            
            if (isset($context['user'])) {
                $scope->setUser([
                    'id' => $context['user']['id'],
                    'email' => $context['user']['email'],
                    'username' => $context['user']['name'],
                ]);
            }
        });
    }
}
```

### Registering Custom Channels

Add your custom channel to the `config/app-context.php` file:

```php
'channels' => [
    // Built-in channels
    JuniorFontenele\LaravelAppContext\Channels\LogChannel::class,

    // Add your custom channels here
    App\Context\Channels\SentryChannel::class,
    App\Context\Channels\CacheChannel::class,
],
```

Or register programmatically:

```php
use JuniorFontenele\LaravelAppContext\Facades\AppContext;
use App\Context\Channels\SentryChannel;

public function boot()
{
    AppContext::addChannel(new SentryChannel());
}
```

## Built-in Providers

### TimestampProvider
Adds the current timestamp to the context.

### AppProvider
Collects application information (name, environment, debug mode, URL, timezone, locale, origin).

### HostProvider
Collects host information (hostname and IP address).

### RequestProvider
Collects HTTP request information (only runs for web requests).

### UserProvider
Collects authenticated user information (only runs when a user is authenticated).

## Built-in Channels

### LogChannel
Registers context in Laravel's Context system (available since Laravel 11), making it automatically available in all application logs. The context is added using `Context::add()` and will be included in every log entry.

## Environment Variables

Control the package behavior with these environment variables:

```env
# Enable/disable the package
LARAVEL_APP_CONTEXT_ENABLED=true
```

## Use Cases

- **Enhanced Logging**: Automatically add rich context to all your logs through Laravel's Context system
- **Debugging**: Track request flow with complete context information available in every log entry
- **Error Tracking**: Register context in Sentry or similar services to get detailed error reports
- **Performance Monitoring**: Add context to APM tools for better performance insights
- **Auditing**: Track user actions with complete environmental context

## Testing

```bash
composer test
```

## Credits

- [Junior Fontenele](https://github.com/juniorfontenele)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
