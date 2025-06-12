<?php
declare(strict_types=1);

use Izerus\SimpleRotatingLogger\LogBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use sgoettsch\monologRotatingFileHandler\Handler\monologRotatingFileHandler as RotatingFileHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Izerus\SimpleRotatingLogger\LogBuilder
 */
class LogBuilderTest extends TestCase
{
    const LOG_PATH = __DIR__ . '/foo.log';

    /**
     * @covers ::addFileHandler
     */
    public function testAddFileHandler()
    {
        $handlers = (new LogBuilder(self::LOG_PATH))
            ->addFileHandler(self::LOG_PATH, Logger::WARNING)
            ->addFileHandler(self::LOG_PATH, Logger::ERROR)
            ->buildLogger()->getHandlers();
        $accepted = 0;
        foreach ($handlers as $handler) {
            $this->assertInstanceOf(RotatingFileHandler::class, $handler);
            $accepted += in_array($handler->getLevel(), [Logger::DEBUG, Logger::WARNING, Logger::ERROR]);
        }
        $this->assertEquals(3, $accepted);
    }

    /**
     * @covers ::buildLogger
     */
    public function testBuildLoggerProcessor()
    {
        $logger = (new LogBuilder(self::LOG_PATH,))->buildLogger();
        $this->assertInstanceOf(PsrLogMessageProcessor::class, $logger->popProcessor());
    }

    /**
     * @covers ::createDefaultFormatter
     */
    public function testCreateDefaultFormatter()
    {
        $handler = (new LogBuilder(self::LOG_PATH))->buildLogger()->popHandler();
        /** @var RotatingFileHandler $handler */
        $this->assertInstanceOf(LineFormatter::class, $handler->getFormatter());
    }

    /**
     * @covers ::addStdoutHandler
     * @dataProvider levelProvider
     */
    public function testAddStdOutHandler(int $level)
    {
        $logger = (new LogBuilder(self::LOG_PATH))
            ->addStdoutHandler($level)->buildLogger();
        $logger->popHandler();
        $handler = $logger->popHandler();
        $this->assertInstanceOf(StreamHandler::class, $handler);
        $this->assertSame('php://stdout', $handler->getUrl());
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
     * @covers ::setName
     */
    public function testSetName()
    {
        $logger = (new LogBuilder(self::LOG_PATH))->setName('foo')->buildLogger();
        $this->assertSame('foo', $logger->getName());
    }
}
