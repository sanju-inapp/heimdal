<?php

namespace Optimus\Heimdal\Tests;

use PHPUnit\Framework\TestCase;
use Optimus\Heimdal\Formatters\ExceptionFormatter;
use Illuminate\Http\JsonResponse;
use Exception;

class ExceptionFormatterTest extends TestCase
{
    public function testFormatSetsStatusCode500()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new ExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new Exception('Test exception');
        
        $formatter->format($response, $exception, []);
        
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testFormatInProductionMode()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new ExceptionFormatter($config, false);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new Exception('Test exception');
        
        $formatter->format($response, $exception, []);
        $data = $response->getData(true);
        
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('An error occurred.', $data['message']);
    }

    public function testFormatInDebugMode()
    {
        $config = ['server_error_production' => 'An error occurred.'];
        $formatter = new ExceptionFormatter($config, true);
        
        $response = new JsonResponse(['status' => 'error']);
        $exception = new Exception('Test exception message', 123);
        
        $formatter->format($response, $exception, []);
        $data = $response->getData(true);
        
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('exception', $data);
        $this->assertArrayHasKey('line', $data);
        $this->assertArrayHasKey('file', $data);
        $this->assertEquals('Test exception message', $data['message']);
        $this->assertEquals(123, $data['code']);
    }
}
