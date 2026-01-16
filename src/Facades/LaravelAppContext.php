<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelAppContext\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelAppContext extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JuniorFontenele\LaravelAppContext\LaravelAppContext::class;
    }
}
