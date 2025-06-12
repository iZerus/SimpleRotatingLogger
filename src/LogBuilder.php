<?php
declare(strict_types=1);

namespace Izerus\SimpleRotatingLogger;

use Monolog\ErrorHandler;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Monolog\Processor\PsrLogMessageProcessor;
use sgoettsch\monologRotatingFileHandler\Handler\monologRotatingFileHandler as RotatingFileHandler;

class LogBuilder
{
    const DEFAULT_MAX_FILES = 9;
    const DEFAULT_MAX_FILE_SIZE = 10485760;
    /** @var HandlerInterface[] */
    private array $handlers = [];
    /** @var ProcessorInterface[] */
    private array $processors = [];
    private string $name;
    private bool $registerInErrorHandler;

    public function __construct(
        string             $path,
        int                $level = Logger::DEBUG,
        int                $maxFiles = self::DEFAULT_MAX_FILES,
        int                $maxFileSize = self::DEFAULT_MAX_FILE_SIZE,
        FormatterInterface $formatter = null,
        bool               $registerInErrorHandler = true,
        bool               $useDefaultProcessors = true
    )
    {
        if ($useDefaultProcessors) {
            $this->addProcessor(new PsrLogMessageProcessor());
        }
        $this->addFileHandler(
            $path,
            $level,
            $maxFiles,
            $maxFileSize,
            $formatter ?? $this->createDefaultFormatter()
        );
        $this->registerInErrorHandler = $registerInErrorHandler;
        $this->setName('local');
    }

    /** @deprecated  */
    public function addProcessor(ProcessorInterface $processor): self
    {
        $this->processors[] = $processor;
        return $this;
    }

    public function addFileHandler(
        string             $path,
        int                $level = Logger::DEBUG,
        int                $maxFiles = self::DEFAULT_MAX_FILES,
        int                $maxFileSize = self::DEFAULT_MAX_FILE_SIZE,
        FormatterInterface $formatter = null
    ): self
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $handler = new RotatingFileHandler($path, $maxFiles, $maxFileSize, $level);
        $handler->setFormatter($formatter ?? $this->createDefaultFormatter());
        $this->addHandler($handler);
        return $this;
    }

    /** @deprecated  */
    public function createDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter(
            null,
            'Y-m-d H:i:s',
            true,
            true,
            true
        );
    }

    /** @deprecated  */
    public function addHandler(HandlerInterface $handler): self
    {
        $this->handlers[] = $handler;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function addStdoutHandler(int $level = Logger::DEBUG, FormatterInterface $formatter = null): self
    {
        $handler = new StreamHandler('php://stdout', $level);
        $handler->setFormatter($formatter ?? $this->createDefaultFormatter());
        $this->addHandler($handler);
        return $this;
    }

    public function buildLogger(): Logger
    {
        $logger = new Logger($this->name, $this->handlers, $this->processors);
        if ($this->registerInErrorHandler) {
            ErrorHandler::register($logger);
        }
        return $logger;
    }
}
