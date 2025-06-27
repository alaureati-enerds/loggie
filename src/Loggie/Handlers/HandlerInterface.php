<?php

namespace Loggie\Handlers;

interface HandlerInterface
{
    public function write(string $level, string $message): void;
}
