<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelAppContext\ContextManager;

describe('LaravelAppContextServiceProvider', function () {
    it('registers the ContextManager in the container', function () {
        expect(app()->bound(ContextManager::class))->toBeTrue();
        expect(app()->bound('app-context'))->toBeTrue();
    });

    it('resolves ContextManager from container', function () {
        $manager = app(ContextManager::class);

        expect($manager)->toBeInstanceOf(ContextManager::class);
    });

    it('resolves ContextManager using alias', function () {
        $manager = app('app-context');

        expect($manager)->toBeInstanceOf(ContextManager::class);
    });

    it('merges config from package', function () {
        expect(config('app-context'))->toBeArray();
        expect(config('app-context'))->toHaveKey('enabled');
        expect(config('app-context'))->toHaveKey('providers');
        expect(config('app-context'))->toHaveKey('channels');
    });

    it('loads default providers configuration', function () {
        $providers = config('app-context.providers');

        expect($providers)->toBeArray();
        expect($providers)->not()->toBeEmpty();
        expect($providers)->toContain(JuniorFontenele\LaravelAppContext\Providers\TimestampProvider::class);
        expect($providers)->toContain(JuniorFontenele\LaravelAppContext\Providers\AppProvider::class);
        expect($providers)->toContain(JuniorFontenele\LaravelAppContext\Providers\HostProvider::class);
    });

    it('loads default channels configuration', function () {
        $channels = config('app-context.channels');

        expect($channels)->toBeArray();
        expect($channels)->not()->toBeEmpty();
        expect($channels)->toContain(JuniorFontenele\LaravelAppContext\Channels\LogChannel::class);
    });
});
