<?php

namespace Loggie\Utils;

use Psr\Log\LogLevel;

class LoggieLevels
{
    public const DEBUG     = LogLevel::DEBUG;
    public const INFO      = LogLevel::INFO;
    public const NOTICE    = LogLevel::NOTICE;
    public const WARNING   = LogLevel::WARNING;
    public const ERROR     = LogLevel::ERROR;
    public const CRITICAL  = LogLevel::CRITICAL;
    public const ALERT     = LogLevel::ALERT;
    public const EMERGENCY = LogLevel::EMERGENCY;

    private const PRIORITY = [
        LogLevel::DEBUG     => 100,
        LogLevel::INFO      => 200,
        LogLevel::NOTICE    => 250,
        LogLevel::WARNING   => 300,
        LogLevel::ERROR     => 400,
        LogLevel::CRITICAL  => 500,
        LogLevel::ALERT     => 550,
        LogLevel::EMERGENCY => 600,
    ];

    public static function compare(string $levelA, string $levelB): int
    {
        return self::PRIORITY[$levelA] <=> self::PRIORITY[$levelB];
    }
}
