<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelAppContext\Providers\RequestProvider;

describe('RequestProvider', function () {
    it('extends AbstractProvider', function () {
        $provider = new RequestProvider();

        expect($provider)->toBeInstanceOf(JuniorFontenele\LaravelAppContext\Providers\AbstractProvider::class);
    });
});
