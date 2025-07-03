<?php

namespace Loggie\Formatters;

use Loggie\Formatters\FormatterInterface;

/**
 * Formatter che esegue l'interpolazione del messaggio usando il contesto.
 *
 * Sostituisce i segnaposto nel messaggio (es. {user}) con i corrispondenti valori
 * presenti nell'array di contesto. Produce un output leggibile con timestamp e livello.
 */
class InterpolatedFormatter implements FormatterInterface
{
    /**
     * Format il messaggio interpolando i valori del contesto nei segnaposto.
     *
     * @param string $level   Il livello di log (es. debug, info, warning, etc.)
     * @param string $message Il messaggio da interpolare
     * @param array  $context Array associativo con i dati da sostituire nel messaggio
     *
     * @return string La stringa formattata
     */
    public function format(string $level, string $message, array $context = []): string
    {
        $interpolated = $message;

        foreach ($context as $key => $value) {
            $interpolated = str_replace("{{$key}}", (string)$value, $interpolated);
        }

        $date = date('d/m/Y H:i:s');
        return "[{$date}] " . strtoupper($level) . ": {$interpolated}" . PHP_EOL;
    }
}
