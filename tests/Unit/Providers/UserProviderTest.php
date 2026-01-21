<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelAppContext\Providers\UserProvider;

describe('UserProvider', function () {
    it('extends AbstractProvider', function () {
        $provider = new UserProvider();

        expect($provider)->toBeInstanceOf(JuniorFontenele\LaravelAppContext\Providers\AbstractProvider::class);
    });
});
