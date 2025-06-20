<?php
declare(strict_types=1);

namespace Izerus\SimpleRotatingLogger;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use sgoettsch\monologRotatingFileHandler\Handler\monologRotatingFileHandler as RotatingFileHandler;

class LogBuilder
{
    const DEFAULT_MAX_FILES = 9;
    const DEFAULT_MAX_FILE_SIZE = 10485760;
    /** @var HandlerInterface[] */
    private array $handlers = [];
    private string $name;

    public function __construct(
        string $path,
        int    $level = Logger::DEBUG,
        int    $maxFiles = self::DEFAULT_MAX_FILES,
        int    $maxFileSize = self::DEFAULT_MAX_FILE_SIZE
    )
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $handler = new RotatingFileHandler($path, $maxFiles, $maxFileSize, $level);
        $handler->setFormatter($this->createDefaultFormatter());
        $this->handlers[] = $handler;
        $this->setName('local');
    }

    private function createDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter(
            null,
            'Y-m-d H:i:s',
            true,
            true,
            true
        );
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function addStdoutHandler(int $level = Logger::DEBUG): self
    {
        $handler = new StreamHandler('php://stdout', $level);
        $handler->setFormatter($this->createDefaultFormatter());
        $this->handlers[] = $handler;
        return $this;
    }

    public function buildLogger(): Logger
    {
        return new Logger($this->name, $this->handlers, [
            new PsrLogMessageProcessor()
        ]);
    }
}
