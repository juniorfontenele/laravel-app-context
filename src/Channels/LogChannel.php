<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelAppContext\Channels;

use Illuminate\Support\Facades\Context;
use JuniorFontenele\LaravelAppContext\Contracts\ContextChannel;

class LogChannel implements ContextChannel
{
    public function registerContext(array $context): void
    {
        Context::add($context);
    }
}
