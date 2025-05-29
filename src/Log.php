<?php
declare(strict_types=1);

namespace Izerus\SimpleRotatingLogger;

use Monolog\Logger;

final class Log
{
    private static Logger $logger;

    public static function build(LogBuilder $builder): void
    {
        self::$logger = $builder->buildLogger();
    }

    public static function debug(string $message, array $context = []): void
    {
        self::$logger->debug($message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::$logger->info($message, $context);
    }

    public static function notice(string $message, array $context = []): void
    {
        self::$logger->notice($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::$logger->warning($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::$logger->error($message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::$logger->critical($message, $context);
    }

    public static function alert(string $message, array $context = []): void
    {
        self::$logger->alert($message, $context);
    }

    public static function emergency(string $message, array $context = []): void
    {
        self::$logger->emergency($message, $context);
    }

    public static function getLogger(string $name = null): Logger
    {
        return $name !== null ? self::$logger->withName($name) : self::$logger;
    }

}
