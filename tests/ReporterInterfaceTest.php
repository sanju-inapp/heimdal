<?php

namespace Optimus\Heimdal\Tests;

use PHPUnit\Framework\TestCase;
use Optimus\Heimdal\Reporters\ReporterInterface;
use Exception;

class DummyReporter implements ReporterInterface
{
    private $config;

    public function __invoke(array $config)
    {
        $this->config = $config;
        return $this;
    }

    public function report(\Throwable $e)
    {
        return ['dummy_id' => 'test-123', 'message' => $e->getMessage()];
    }
}

class ReporterInterfaceTest extends TestCase
{
    public function testReporterInterfaceImplementation()
    {
        $reporter = new DummyReporter();
        $this->assertInstanceOf(ReporterInterface::class, $reporter);
    }

    public function testReporterCanBeInvoked()
    {
        $reporter = new DummyReporter();
        $config = ['key' => 'value'];
        $instance = $reporter($config);
        
        $this->assertInstanceOf(DummyReporter::class, $instance);
    }

    public function testReporterReportMethod()
    {
        $reporter = new DummyReporter();
        $reporter([]);
        
        $exception = new Exception('Test error');
        $response = $reporter->report($exception);
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('dummy_id', $response);
        $this->assertEquals('test-123', $response['dummy_id']);
        $this->assertEquals('Test error', $response['message']);
    }
}
