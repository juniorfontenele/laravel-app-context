<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelAppContext\Providers\AppProvider;

describe('AppProvider', function () {
    it('should run by default', function () {
        $provider = new AppProvider();

        expect($provider->shouldRun())->toBeTrue();
    });

    it('extends AbstractProvider', function () {
        $provider = new AppProvider();

        expect($provider)->toBeInstanceOf(JuniorFontenele\LaravelAppContext\Providers\AbstractProvider::class);
    });
});
