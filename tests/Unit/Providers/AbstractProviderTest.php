<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelAppContext\Contracts\ContextProvider;
use JuniorFontenele\LaravelAppContext\Providers\AbstractProvider;

describe('AbstractProvider', function () {
    it('implements ContextProvider interface', function () {
        $provider = new class extends AbstractProvider
        {
            public function getContext(): array
            {
                return [];
            }
        };

        expect($provider)->toBeInstanceOf(ContextProvider::class);
    });

    it('should run by default', function () {
        $provider = new class extends AbstractProvider
        {
            public function getContext(): array
            {
                return [];
            }
        };

        expect($provider->shouldRun())->toBeTrue();
    });

    it('can be extended to override shouldRun', function () {
        $provider = new class extends AbstractProvider
        {
            public function shouldRun(): bool
            {
                return false;
            }

            public function getContext(): array
            {
                return [];
            }
        };

        expect($provider->shouldRun())->toBeFalse();
    });
});
