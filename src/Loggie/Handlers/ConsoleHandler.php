<?php

namespace Loggie\Handlers;

use Loggie\Formatters\FormatterInterface;
use Loggie\Formatters\LineFormatter;
use Loggie\Utils\LoggieLevels;


/**
 * Handler che scrive i log sulla console (STDOUT o STDERR).
 *
 * Supporta la colorazione opzionale in base al livello di log
 * e permette la personalizzazione del formatter.
 */
class ConsoleHandler implements HandlerInterface
{
    private FormatterInterface $formatter;
    private string $minLevel;
    private $stream;

    private bool $enableColors = false;

    private const COLORS = [
        'debug' => "\033[90m",
        'info' => "\033[32m",
        'notice' => "\033[36m",
        'warning' => "\033[33m",
        'error' => "\033[31m",
        'critical' => "\033[35m",
        'alert' => "\033[41m",
        'emergency' => "\033[41;1m",
    ];
    private const RESET = "\033[0m";

    /**
     * @param resource $stream   Stream su cui scrivere (es. STDOUT, STDERR)
     * @param string   $minLevel Livello minimo da loggare (es. 'debug', 'error', etc.)
     */
    public function __construct($stream = STDOUT, string $minLevel = LoggieLevels::DEBUG)
    {
        $this->stream = $stream;
        $this->minLevel = $minLevel;
        $this->formatter = new LineFormatter();
    }

    /**
     * Imposta il formatter da utilizzare per formattare i messaggi di log.
     *
     * @param FormatterInterface $formatter Formatter da utilizzare
     */
    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * Abilita o disabilita la colorazione dei messaggi in output.
     *
     * @param bool $enable True per abilitare, false per disabilitare
     */
    public function enableColors(bool $enable = true): void
    {
        $this->enableColors = $enable;
    }

    /**
     * Scrive il messaggio formattato sullo stream selezionato se il livello è sufficiente.
     *
     * @param string $level   Livello del messaggio
     * @param string $message Contenuto del messaggio
     * @param array  $context Contesto associato al log
     */
    public function write(string $level, string $message, array $context = []): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        $line = $this->formatter->format($level, $message, $context);
        if ($this->enableColors && isset(self::COLORS[$level])) {
            $line = self::COLORS[$level] . $line . self::RESET;
        }
        fwrite($this->stream, $line);
    }
}
