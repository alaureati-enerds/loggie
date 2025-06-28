<?php

namespace Loggie\Handlers;

class NullHandler implements HandlerInterface
{
    public function write(string $level, string $message, array $context = []): void
    {
        // Non fa nulla.
    }
}
