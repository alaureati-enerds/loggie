<?php

namespace Loggie\Formatters;

use Loggie\Formatters\FormatterInterface;

class InterpolatedFormatter implements FormatterInterface
{
    public function format(string $level, string $message, array $context = []): string
    {
        $interpolated = $message;

        foreach ($context as $key => $value) {
            $interpolated = str_replace("{{$key}}", (string)$value, $interpolated);
        }

        $date = date('Y-m-d H:i:s');
        return "[{$date}] " . strtoupper($level) . ": {$interpolated}" . PHP_EOL;
    }
}
