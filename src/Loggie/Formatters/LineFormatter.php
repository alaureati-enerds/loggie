<?php

namespace Loggie\Formatters;

use Loggie\Formatters\FormatterInterface;

class LineFormatter implements FormatterInterface
{
    public function format(string $level, string $message, array $context = []): string
    {
        $date = date('Y-m-d H:i:s');
        return "[{$date}] " . strtoupper($level) . ": {$message}" . PHP_EOL;
    }
}
