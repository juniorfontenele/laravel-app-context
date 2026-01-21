<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelAppContext\ContextManager;
use JuniorFontenele\LaravelAppContext\Facades\AppContext;
use JuniorFontenele\LaravelAppContext\Providers\TimestampProvider;

describe('AppContext Facade', function () {
    beforeEach(function () {
        // Limpar o contexto antes de cada teste
        AppContext::clear();
    });

    it('resolves to ContextManager', function () {
        $facade = AppContext::getFacadeRoot();

        expect($facade)->toBeInstanceOf(ContextManager::class);
    });

    it('can call all() method through facade', function () {
        AppContext::addProvider(new TimestampProvider());

        $context = AppContext::all();

        expect($context)->toBeArray();
        expect($context)->toHaveKey('timestamp');
    });

    it('can call get() method through facade', function () {
        AppContext::addProvider(new TimestampProvider());

        $timestamp = AppContext::get('timestamp');

        expect($timestamp)->toBeString();
    });

    it('can call set() method through facade', function () {
        AppContext::set('custom.key', 'facade-value');

        expect(AppContext::get('custom.key'))->toBe('facade-value');
    });

    it('can call clear() method through facade', function () {
        AppContext::set('test', 'value');
        expect(AppContext::get('test'))->toBe('value');

        AppContext::clear();

        AppContext::set('dummy', 'dummy'); // Para forçar resolução
        expect(AppContext::get('test'))->toBeNull();
    });

    it('can call addProvider() method through facade', function () {
        $provider = new TimestampProvider();

        $result = AppContext::addProvider($provider);

        expect($result)->toBeInstanceOf(ContextManager::class);
    });

    it('can call resolveContext() method through facade', function () {
        AppContext::clear();
        AppContext::addProvider(new TimestampProvider());

        $context = AppContext::resolveContext();

        expect($context)->toBeArray();
        expect($context)->toHaveKey('timestamp');
    });

    it('can chain methods through facade', function () {
        AppContext::clear()
            ->set('key1', 'value1')
            ->set('key2', 'value2');

        expect(AppContext::get('key1'))->toBe('value1');
        expect(AppContext::get('key2'))->toBe('value2');
    });

    it('returns default value when key not found', function () {
        AppContext::clear();

        expect(AppContext::get('nonexistent', 'default'))->toBe('default');
    });

    it('handles nested keys through facade', function () {
        AppContext::clear();
        AppContext::set('nested.deep.key', 'nested-value');

        expect(AppContext::get('nested.deep.key'))->toBe('nested-value');
    });
});
