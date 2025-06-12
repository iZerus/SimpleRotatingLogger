<?php
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
declare(strict_types=1);


use Izerus\SimpleRotatingLogger\LogBuilder;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use PHPUnit\Framework\TestCase;
use sgoettsch\monologRotatingFileHandler\Handler\monologRotatingFileHandler as RotatingFileHandler;

/**
 * @coversDefaultClass \Izerus\SimpleRotatingLogger\LogBuilder
 */
class LogBuilderConstructorTest extends TestCase
{
    const LOG_PATH = __DIR__ . '/foo.log';

    private function getPrivateProperty(object $object, string $property)
    {
        $reflection = new ReflectionClass($object);
        /** @noinspection PhpUnhandledExceptionInspection */
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithPath()
    {
        $handler = (new LogBuilder(
            self::LOG_PATH
        ))->buildLogger()->popHandler();
        $this->assertInstanceOf(RotatingFileHandler::class, $handler);
        $this->assertEquals(self::LOG_PATH, $handler->getUrl());
    }

    /**
     * @covers ::__construct
     * @dataProvider levelProvider
     */
    public function testConstructWithLevel(int $level)
    {
        /** @var RotatingFileHandler $handler */
        $handler = (new LogBuilder(
            self::LOG_PATH,
            $level
        ))->buildLogger()->popHandler();
        $this->assertSame($level, $handler->getLevel());
    }

    public function levelProvider(): array
    {
        return [
            [Logger::DEBUG],
            [Logger::INFO]
        ];
    }

    /**
     * @covers ::__construct
     * @dataProvider maxFilesAndSizeProvider
     */
    public function testConstructWithMaxFiles(int $maxFiles)
    {
        /** @var RotatingFileHandler $handler */
        $handler = (new LogBuilder(
            self::LOG_PATH,
            Logger::DEBUG, $maxFiles
        ))->buildLogger()->popHandler();
        $this->assertSame($maxFiles, $this->getPrivateProperty($handler, 'maxFiles'));
    }

    public function maxFilesAndSizeProvider(): array
    {
        return [
            [1],
            [2]
        ];
    }

    /**
     * @covers ::__construct
     * @dataProvider maxFilesAndSizeProvider
     */
    public function testConstructWithMaxFileSize(int $maxFileSize)
    {
        /** @var RotatingFileHandler $handler */
        $handler = (new LogBuilder(
            self::LOG_PATH,
            Logger::DEBUG,
            9,
            $maxFileSize
        ))->buildLogger()->popHandler();
        $this->assertSame($maxFileSize, $this->getPrivateProperty($handler, 'maxFileSize'));
    }

    /**
     * @covers ::buildLogger
     */
    public function testBuildLoggerProcessor()
    {
        $logger = (new LogBuilder(self::LOG_PATH,))->buildLogger();
        $this->assertInstanceOf(PsrLogMessageProcessor::class, $logger->popProcessor());
    }
}
