<?php

namespace Loggie;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Loggie\Handlers\HandlerInterface;

/**
 * Logger principale della libreria Loggie.
 *
 * Implementa l'interfaccia PSR-3 LoggerInterface e permette di scrivere messaggi
 * su uno o più handler configurati. Ogni handler può gestire i log in modo diverso
 * (es. file, database, console).
 */
class Logger implements LoggerInterface
{
    /** @var HandlerInterface[] */
    private array $handlers = [];

    /**
     * @param HandlerInterface[] $handlers Lista di handler da utilizzare per la scrittura dei log.
     */
    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    /**
     * Aggiunge un singolo handler al logger.
     *
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * Aggiunge più handler contemporaneamente.
     *
     * @param HandlerInterface[] $handlers
     */
    public function addHandlers(array $handlers): void
    {
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /**
     * Scrive un messaggio di log con un livello specificato.
     *
     * @param mixed  $level   Il livello di log (stringa conforme a PSR-3).
     * @param string|\Stringable $message Il messaggio di log, con o senza segnaposto.
     * @param array  $context Dati di contesto da interpolare nel messaggio.
     *
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $interpolated = $this->interpolate($message, $context);
        foreach ($this->handlers as $handler) {
            $handler->write($level, $interpolated, $context);
        }
    }

    /**
     * Logga un messaggio di livello EMERGENCY.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
    /**
     * Logga un messaggio di livello ALERT.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }
    /**
     * Logga un messaggio di livello CRITICAL.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }
    /**
     * Logga un messaggio di livello ERROR.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }
    /**
     * Logga un messaggio di livello WARNING.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    /**
     * Logga un messaggio di livello NOTICE.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }
    /**
     * Logga un messaggio di livello INFO.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }
    /**
     * Logga un messaggio di livello DEBUG.
     *
     * @param string|\Stringable $message Il messaggio da loggare.
     * @param array $context Dati di contesto opzionali.
     *
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Esegue l'interpolazione dei segnaposto presenti nel messaggio con i valori del contesto.
     *
     * I segnaposto nel messaggio devono essere nel formato {chiave} e verranno sostituiti
     * con il valore corrispondente all'interno dell'array $context.
     *
     * @param string $message Il messaggio contenente segnaposto da interpolare.
     * @param array $context  I dati di contesto da usare per la sostituzione.
     *
     * @return string Il messaggio interpolato con i valori del contesto.
     */
    private function interpolate(string $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = (string) $val;
        }
        return strtr($message, $replace);
    }
}
