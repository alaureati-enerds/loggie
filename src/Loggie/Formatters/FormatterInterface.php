<?php

namespace Loggie\Formatters;

interface FormatterInterface
{
    public function format(string $level, string $message, array $context = []): string;
}
