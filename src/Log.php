<?php
declare(strict_types=1);

namespace Izerus\SimpleRotatingLogger;

use LogicException;
use Monolog\ErrorHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * A facade for using a logger
 */
final class Log
{
    private static LoggerInterface $logger;

    /**
     * Build a logger using a builder
     */
    public static function build(LogBuilder $builder): void
    {
        self::setLogger($builder->buildLogger());
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

    public static function getLogger(string $name = null): LoggerInterface
    {
        if ($name === null) {
            return self::$logger;
        }
        $logger = self::$logger;
        if ($logger instanceof Logger) {
            return $logger->withName($name);
        }
        throw new LogicException('Object is not instance of ' . Logger::class);
    }

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
        ErrorHandler::register($logger);
    }

}
