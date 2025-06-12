<?php
declare(strict_types=1);

use Izerus\SimpleRotatingLogger\LogBuilder;
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
}
