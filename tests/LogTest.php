<?php
declare(strict_types=1);

use Izerus\SimpleRotatingLogger\Log;
use Izerus\SimpleRotatingLogger\LogBuilder;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

/**
 * @coversDefaultClass Izerus\SimpleRotatingLogger\Log
 * @uses Izerus\SimpleRotatingLogger\LogBuilder
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
        Log::setLogger($this->logger);
        $this->assertSame(Log::getLogger(), $this->logger);
    }

    /**
     * @covers ::debug
     * @covers ::setLogger
     */
    public function testDebug(): void
    {
        Log::debug('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'debug'));
    }

    /**
     * @covers ::info
     * @covers ::setLogger
     */
    public function testInfo(): void
    {
        Log::info('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'info'));
    }

    /**
     * @covers ::notice
     * @covers ::setLogger
     */
    public function testNotice(): void
    {
        Log::notice('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'notice'));
    }

    /**
     * @covers ::warning
     * @covers ::setLogger
     */
    public function testWarning(): void
    {
        Log::warning('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'warning'));
    }

    /**
     * @covers ::error
     * @covers ::setLogger
     */
    public function testError(): void
    {
        Log::error('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'error'));
    }

    /**
     * @covers ::critical
     * @covers ::setLogger
     */
    public function testCritical(): void
    {
        Log::critical('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'critical'));
    }

    /**
     * @covers ::alert
     * @covers ::setLogger
     */
    public function testAlert(): void
    {
        Log::alert('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'alert'));
    }

    /**
     * @covers ::emergency
     * @covers ::setLogger
     */
    public function testEmergency(): void
    {
        Log::emergency('foo');
        $this->assertTrue($this->logger->hasRecord('foo', 'emergency'));
    }

    /**
     * @covers ::getLogger
     * @covers ::setLogger
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
     * @covers ::setLogger
     */
    public function testGetLogWithNameForNonLoggerObject(): void
    {
        $this->expectException(LogicException::class);
        Log::getLogger('foo');
    }

    /**
     * @covers ::build
     * @covers ::setLogger
     * @covers ::getLogger
     */
    public function testBuild(): void
    {
        $builder = new LogBuilder(__DIR__ . '/test.log');
        $builder->setName('foo');
        $originalLogger = $builder->buildLogger();
        Log::build($builder);
        $logger = Log::getLogger();
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame($originalLogger->getName(), $logger->getName());
    }
}
