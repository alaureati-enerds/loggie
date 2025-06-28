<?php

namespace Loggie\Utils;

use Loggie\Logger;
use Loggie\Handlers\ConsoleHandler;
use Loggie\Handlers\FileHandler;
use Loggie\Handlers\NullHandler;
use Loggie\Handlers\HandlerInterface;
use Loggie\Formatters\LineFormatter;
use Loggie\Formatters\InterpolatedFormatter;
use RuntimeException;

class LoggieFactory
{
    public static function fromArray(array $config): Logger
    {
        if (!isset($config['handlers']) || !is_array($config['handlers'])) {
            throw new RuntimeException("Invalid configuration: 'handlers' key missing or not an array.");
        }

        $handlers = [];

        foreach ($config['handlers'] as $handlerConf) {
            $handlers[] = self::buildHandler($handlerConf);
        }

        return new Logger($handlers);
    }

    private static function buildHandler(array $conf): HandlerInterface
    {
        $type = $conf['type'] ?? 'null';
        $level = $conf['level'] ?? 'debug';
        $formatterType = $conf['formatter'] ?? 'line';

        switch ($type) {
            case 'console':
                $handler = new ConsoleHandler(STDOUT, $level);
                if (!empty($conf['colors'])) {
                    $handler->enableColors();
                }
                break;

            case 'file':
                if (empty($conf['path'])) {
                    throw new RuntimeException("File handler requires a 'path' key.");
                }
                $handler = new FileHandler($conf['path'], $level);
                break;

            case 'database':
                if (empty($conf['pdo']) || !$conf['pdo'] instanceof \PDO) {
                    throw new RuntimeException("Database handler requires a valid 'pdo' instance.");
                }
                $table = $conf['table'] ?? 'log';
                $channel = $conf['channel'] ?? 'default';
                $handler = new \Loggie\Handlers\DatabaseHandler($conf['pdo'], $table, $level, $channel);
                break;

            case 'null':
            default:
                $handler = new NullHandler();
                break;
        }

        $formatter = self::buildFormatter($formatterType);
        if (method_exists($handler, 'setFormatter')) {
            $handler->setFormatter($formatter);
        }

        return $handler;
    }

    private static function buildFormatter(string $type)
    {
        return match ($type) {
            'interpolated' => new InterpolatedFormatter(),
            'line' => new LineFormatter(),
        };
    }
}
