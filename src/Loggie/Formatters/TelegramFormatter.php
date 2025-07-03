<?php

namespace Loggie\Formatters;

use Loggie\Formatters\FormatterInterface;

/**
 * Formatter specifico per Telegram, che genera messaggi compatibili con Markdown V2.
 *
 * Questo formatter è pensato per l'uso con TelegramHandler e restituisce
 * stringhe formattate con emoji in base al livello di log, timestamp,
 * messaggio principale ed eventuale contesto formattato come lista chiave/valore.
 *
 * Caratteristiche:
 * - Utilizzo di emoji per rappresentare i livelli (es. 🔴 per errori)
 * - Escape dei caratteri Markdown V2
 * - Supporto al timestamp personalizzabile
 */
class TelegramFormatter implements FormatterInterface
{
    protected string $dateFormat;

    /**
     * Costruisce un nuovo formatter Telegram.
     *
     * @param string $dateFormat Formato data per il timestamp. Default: 'd/m/Y H:i:s'
     */
    public function __construct(string $dateFormat = 'd/m/Y H:i:s')
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * Restituisce il messaggio formattato per Telegram.
     *
     * @param string $level   Livello del log (es. 'error', 'info')
     * @param string $message Messaggio principale
     * @param array  $context Contesto aggiuntivo per il log
     *
     * @return string Messaggio formattato compatibile con Markdown V2
     */
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

    /**
     * Restituisce un'emoji associata al livello di log.
     *
     * @param string $level Il livello del log
     * @return string Emoji rappresentativa del livello
     */
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

    /**
     * Esegue l'escape dei caratteri speciali secondo le regole del Markdown V2 di Telegram.
     *
     * @param string $text Testo da formattare
     * @return string Testo con escape applicato
     */
    protected function escapeMarkdown(string $text): string
    {
        $escapeChars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($escapeChars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        return $text;
    }

    /**
     * Formatta il contesto in righe chiave:valore con escape Markdown.
     *
     * @param array $context Array associativo del contesto
     * @return string Contesto formattato per Telegram
     */
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
