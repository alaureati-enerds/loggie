<?php

namespace Loggie\Formatters;

use Loggie\Formatters\FormatterInterface;

/**
 * Formatter predefinito che genera una singola riga testuale per ogni log.
 *
 * Il formato della riga è: [YYYY-MM-DD HH:MM:SS] LIVELLO: messaggio
 * Utile per la scrittura in file di log o output console leggibile.
 */
class LineFormatter implements FormatterInterface
{
    /**
     * Format il messaggio in una stringa leggibile con data e livello.
     *
     * @param string $level   Il livello di log (es. debug, info, warning, etc.)
     * @param string $message Il messaggio da scrivere
     * @param array  $context Dati di contesto (non usati da questo formatter)
     *
     * @return string La stringa formattata
     */
    public function format(string $level, string $message, array $context = []): string
    {
        $date = date('d/m/Y H:i:s');
        return "[{$date}] " . strtoupper($level) . ": {$message}" . PHP_EOL;
    }
}
