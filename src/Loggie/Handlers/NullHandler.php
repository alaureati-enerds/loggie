<?php

namespace Loggie\Handlers;

/**
 * Handler che ignora completamente tutti i messaggi di log.
 *
 * Utile per ambienti di test o per disabilitare il logging senza rimuovere il codice.
 */
class NullHandler implements HandlerInterface
{
    /**
     * Metodo richiesto dall'interfaccia ma non esegue alcuna azione.
     *
     * @param string $level   Livello del messaggio (ignorato).
     * @param string $message Messaggio di log (ignorato).
     * @param array  $context Contesto del messaggio (ignorato).
     */
    public function write(string $level, string $message, array $context = []): void
    {
        // Non fa nulla.
    }
}
