<?php
declare(strict_types=1);

use Izerus\SimpleRotatingLogger\Log;
use Izerus\SimpleRotatingLogger\LogBuilder;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;

/**
 * @coversDefaultClass Izerus\SimpleRotatingLogger\Log
 */
class LogTest extends TestCase
{
    private TestLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new TestLogger();
        Log::setLogger($this->logger);
    }

    /**
     * @covers ::setLogger
     * @covers ::getLogger
     */
    public function testSetAndGetLogger(): void
    {
        $logger = new NullLogger();
        Log::setLogger($logger);
        $this->assertSame(Log::getLogger(), $logger);
    }

    /**
     * @covers ::debug
     * @covers ::info
     * @covers ::notice
     * @covers ::warning
     * @covers ::error
     * @dataProvider levelProvider
     */
    public function testLog(string $level): void
    {
        Log::{$level}('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], $level));
    }

    public function levelProvider(): array
    {
        return [
            ['debug'],
            ['info'],
            ['notice'],
            ['warning'],
            ['error'],
        ];
    }

    /**
     * @covers ::getLogger
     */
    public function testGetLogWithName(): void
    {
        Log::setLogger(new Logger('logger'));
        $logger = Log::getLogger('foo');
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('foo', $logger->getName());
    }

    /**
     * @covers ::getLogger
     */
    public function testGetLogWithNameForNonLoggerObject(): void
    {
        Log::setLogger(new TestLogger());
        $this->expectException(LogicException::class);
        Log::getLogger('foo');
    }

    /**
     * @covers ::build
     */
    public function testBuild(): void
    {
        $builder = (new LogBuilder(__DIR__ . '/foo.log'))->setName('foo');
        Log::build($builder);
        $logger = Log::getLogger();
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame($builder->buildLogger()->getName(), $logger->getName());
    }
}
