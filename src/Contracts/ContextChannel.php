<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelAppContext\Contracts;

interface ContextChannel
{
    /**
     * Send the context to the channel
     */
    public function send(array $context): void;
}
