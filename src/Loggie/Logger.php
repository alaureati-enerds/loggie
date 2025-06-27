<?php

namespace Loggie;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Loggie\Handlers\HandlerInterface;

class Logger implements LoggerInterface
{
    /** @var HandlerInterface[] */
    private array $handlers = [];

    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    public function log($level, $message, array $context = []): void
    {
        $interpolated = $this->interpolate($message, $context);
        foreach ($this->handlers as $handler) {
            $handler->write($level, $interpolated);
        }
    }

    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }
    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }
    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }
    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }
    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }
    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    private function interpolate(string $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = (string) $val;
        }
        return strtr($message, $replace);
    }
}
