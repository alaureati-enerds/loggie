<?php

namespace Loggie\Handlers;

use Loggie\Utils\LoggieLevels;
use Loggie\Formatters\FormatterInterface;
use Loggie\Formatters\LineFormatter;

class FileHandler implements HandlerInterface
{
    private string $filePath;
    private string $minLevel = 'debug';
    private FormatterInterface $formatter;

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

    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    public function write(string $level, string $message, array $context = []): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        $line = $this->formatter->format($level, $message, $context);
        file_put_contents($this->filePath, $line, FILE_APPEND);
    }
}
