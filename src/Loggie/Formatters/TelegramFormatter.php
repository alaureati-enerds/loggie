<?php

namespace Loggie\Formatters;

use Loggie\Formatters\FormatterInterface;

class TelegramFormatter implements FormatterInterface
{
    protected string $dateFormat;

    public function __construct(string $dateFormat = 'd/m/Y H:i:s')
    {
        $this->dateFormat = $dateFormat;
    }

    public function format(string $level, string $message, array $context = []): string
    {
        $emoji = $this->getLevelEmoji($level);
        $timestamp = date($this->dateFormat);

        $escapedMessage = $this->escapeMarkdown($message);
        $escapedContext = $this->formatContext($context);

        $formatted = "{$emoji} *[" . strtoupper($level) . "]* `{$timestamp}`\n";
        $formatted .= "{$escapedMessage}";

        if (!empty($escapedContext)) {
            $formatted .= "\n\n" . $escapedContext;
        }

        return $formatted;
    }

    protected function getLevelEmoji(string $level): string
    {
        return match (strtolower($level)) {
            'debug' => '🟢',
            'info' => '🔵',
            'notice' => '📘',
            'warning' => '🟡',
            'error' => '🔴',
            'critical' => '☠️',
            'alert' => '🚨',
            'emergency' => '🔥',
            default => '❓',
        };
    }

    protected function escapeMarkdown(string $text): string
    {
        $escapeChars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($escapeChars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        return $text;
    }

    protected function formatContext(array $context): string
    {
        if (empty($context)) {
            return '';
        }

        $lines = [];
        foreach ($context as $key => $value) {
            $escapedKey = $this->escapeMarkdown((string)$key);
            $escapedValue = $this->escapeMarkdown(var_export($value, true));
            $lines[] = "`{$escapedKey}`: {$escapedValue}";
        }

        return implode("\n", $lines);
    }
}
