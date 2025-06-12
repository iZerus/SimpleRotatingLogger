<?php
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
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
     */
    public function testDebug(): void
    {
        Log::debug('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'debug'));
    }

    /**
     * @covers ::info
     */
    public function testInfo(): void
    {
        Log::info('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'info'));
    }

    /**
     * @covers ::notice
     */
    public function testNotice(): void
    {
        Log::notice('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'notice'));
    }

    /**
     * @covers ::warning
     */
    public function testWarning(): void
    {
        Log::warning('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'warning'));
    }

    /**
     * @covers ::error
     */
    public function testError(): void
    {
        Log::error('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'error'));
    }

    /**
     * @covers ::critical
     */
    public function testCritical(): void
    {
        Log::critical('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'critical'));
    }

    /**
     * @covers ::alert
     */
    public function testAlert(): void
    {
        Log::alert('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'alert'));
    }

    /**
     * @covers ::emergency
     */
    public function testEmergency(): void
    {
        Log::emergency('foo', ['foo' => 'bar']);
        $this->assertTrue($this->logger->hasRecord(['message' => 'foo', 'context' => ['foo' => 'bar']], 'emergency'));
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
