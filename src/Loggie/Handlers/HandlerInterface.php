<?php

/**
 * Interfaccia per i gestori (handlers) di log.
 *
 * Ogni handler implementa una modalità di scrittura dei messaggi di log
 * (es. file, console, database, ecc.). Gli handler vengono utilizzati dal Logger
 * per delegare la scrittura dei messaggi.
 */

namespace Loggie\Handlers;

interface HandlerInterface
{
    /**
     * Scrive un messaggio di log.
     *
     * @param string $level   Il livello di log (es. debug, info, error, ...), conforme a PSR-3.
     * @param string $message Il messaggio da scrivere. Può essere già interpolato o formattato.
     * @param array  $context (opzionale) Dati di contesto aggiuntivi, se previsti dall’implementazione.
     *
     * @return void
     */
    public function write(string $level, string $message, array $context = []): void;
}
