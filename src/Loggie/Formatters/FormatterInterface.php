<?php

/**
 * Interfaccia per i formatter dei log.
 *
 * Un formatter è responsabile della trasformazione dei dati del log
 * (livello, messaggio e contesto) in una stringa pronta per l'output.
 * Ogni handler può usare un formatter per personalizzare il formato del log.
 */

namespace Loggie\Formatters;

interface FormatterInterface
{
    /**
     * Format il messaggio di log in una stringa.
     *
     * @param string $level     Il livello del log (es. debug, info, error, ...), conforme a PSR-3.
     * @param string $message   Il messaggio da formattare.
     * @param array  $context   (opzionale) Dati di contesto aggiuntivi, utili per l'interpolazione.
     *
     * @return string La stringa formattata da scrivere.
     */
    public function format(string $level, string $message, array $context = []): string;
}
