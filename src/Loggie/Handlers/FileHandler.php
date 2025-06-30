<?php

namespace Loggie\Handlers;

use Loggie\Utils\LoggieLevels;
use Loggie\Formatters\FormatterInterface;
use Loggie\Formatters\LineFormatter;

/**
 * Handler che scrive i log su file.
 *
 * Verifica la presenza e i permessi della directory/file in fase di costruzione.
 * Supporta la definizione di un livello minimo e l'utilizzo di formatter personalizzati.
 */
class FileHandler implements HandlerInterface
{
    private string $filePath;
    private string $minLevel = 'debug';
    private FormatterInterface $formatter;

    /**
     * @param string $filePath Percorso completo del file su cui scrivere i log.
     * @param string $minLevel Livello minimo da loggare (default: 'debug').
     *
     * @throws \RuntimeException Se il file o la directory non sono scrivibili o non possono essere creati.
     */
    public function __construct(string $filePath, string $minLevel = LoggieLevels::DEBUG)
    {
        $dir = dirname($filePath);

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException("Impossibile creare la directory di log: {$dir}");
            }
        }

        if (!is_writable($dir)) {
            throw new \RuntimeException("La directory di log non è scrivibile: {$dir}");
        }

        if (file_exists($filePath) && !is_writable($filePath)) {
            throw new \RuntimeException("Il file di log esiste ma non è scrivibile: {$filePath}");
        }

        $this->filePath = $filePath;
        $this->minLevel = $minLevel;
        $this->formatter = new LineFormatter();
    }

    /**
     * Imposta un formatter per formattare i messaggi prima della scrittura.
     *
     * @param FormatterInterface $formatter Il formatter da utilizzare.
     */
    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * Scrive un messaggio di log nel file, se il livello è sufficiente.
     *
     * @param string $level   Livello del messaggio di log.
     * @param string $message Messaggio da scrivere.
     * @param array  $context Contesto associato (opzionale).
     */
    public function write(string $level, string $message, array $context = []): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        $line = $this->formatter->format($level, $message, $context);
        file_put_contents($this->filePath, $line, FILE_APPEND);
    }
}
