<?php

namespace Loggie\Handlers;

use Loggie\Utils\LoggieLevels;

class FileHandler implements HandlerInterface
{
    private string $filePath;
    private string $minLevel = 'debug';

    public function __construct(string $filePath, string $minLevel = LoggieLevels::DEBUG)
    {
        $dir = dirname($filePath);

        // Crea la directory se non esiste
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException("Impossibile creare la directory di log: {$dir}");
            }
        }

        // Controlla se la directory è scrivibile
        if (!is_writable($dir)) {
            throw new \RuntimeException("La directory di log non è scrivibile: {$dir}");
        }

        // Se il file esiste, verifica che sia scrivibile
        if (file_exists($filePath) && !is_writable($filePath)) {
            throw new \RuntimeException("Il file di log esiste ma non è scrivibile: {$filePath}");
        }

        $this->filePath = $filePath;
        $this->minLevel = $minLevel;
    }

    public function write(string $level, string $message): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        $date = date('Y-m-d H:i:s');
        $line = "[{$date}] {$level}: {$message}" . PHP_EOL;
        file_put_contents($this->filePath, $line, FILE_APPEND);
    }
}
